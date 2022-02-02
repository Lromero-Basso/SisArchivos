<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Areas;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap4View;



/**
 * 
 * @Route("/area")
 */
class AreaController extends BaseController
{

    /**
     * @Route("/create", name="createArea")
     * @Method({"GET", "POST"})
     */
    public function createArea(Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Nuevo Area", "createArea");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $formArea = $request->get("Area");
        if($formArea != null){
            $area = $this->findAreaByName($entityManager, strtoupper($formArea['nomArea']));
   
            if(empty($area)){
                $area = new Areas();
                $area->setNomArea(trim(strtoupper($formArea['nomArea'])));
                
                $entityManager->persist($area);
                $entityManager->flush();
                $this->addFlash(
                    'notice',
                    '¡Se cargó correctamente el área ' . $area->getNomArea() . '!'
                );
            }
            else{
                $this->addFlash(
                    'error',
                    'El área que intenta cargar ya existe'
                );
                return $this->redirectToRoute('createArea');
            }
           
            return $this->redirectToRoute('viewAreas');

        }

        return $this->render('area/create.html.twig');
    }

    /**
     * @Route("/view", name="viewAreas")
     * @Method("GET")
     */
    public function viewAreas(Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Áreas", "viewAreas");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $queryBuilder = $entityManager->getRepository('AppBundle:Areas')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($areas, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('area/all.html.twig', array(
            'areas' => $areas,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
        ));
    }

    /**
     * @Route("/edit/{id}", name="editArea")
     * @Method({"GET", "POST"})
     */
    public function editArea(Request $request, $id){
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Editar Area - $id", "editArea");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $area = $entityManager->getRepository(Areas::class)->findOneBy(array('id' => $id));

        $formArea = $request->get("Area");

        if($formArea != null){

            $area->setNomArea(trim(strtoupper($formArea['nomArea'])));
            $entityManager->persist($area);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                '¡Se actualizó correctamente el área ' . $area->getNomArea() . '!'
            );
                   
            return $this->redirectToRoute('viewAreas');

        }

        return $this->render('area/create.html.twig', array(
            'area'          => $area
        ));
    }

    /**
     * @Route("/{id}", name="showArea")
     * @Method("GET")
     */
    public function showArea(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Vista Previa");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");
        
        return $this->render('area/show.html.twig');
    }

    /**
     * @Route("/{id}", name="deleteArea")
     * @Method({"GET", "POST"})
     */
    public function deleteArea(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();
        
        return $this->redirectToRoute('viewAreas');
    }


    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request){
        $session = $request->getSession();
        $filterForm = $this->createForm('AppBundle\Form\AreaFilterType',$this->getNombreAreas());

        //Reset filter
        if($request->get('filter_action') == 'reset'){
            if($session->get('AreaControllerFilter') != null){
                //If null apply reset, is necessary because without the validate, this closed the session
                $session->remove('AreaControllerFilter');
            }
        }

        //Filter action
        if($request->get('filter_action') == 'filter'){
            $filterForm->handleRequest($request);
            
            // Bind values from the request
            if($filterForm->isValid()){
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);

                //Save filter to session
                $filterData = $filterForm->getData();
                $session->set('AreaControllerFilter', $filterData);
            }
        }
        //Este else me deja el filtrado puesto por mas que me vaya a otro lado
        // else{
        //     if($session->has('AreaControllerFilter')){
        //         $filterData = $session->get('AreaControllerFilter');

        //         foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
        //             if (is_object($filter)) {
        //                 $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
        //             }
        //         }

        //         $filterForm = $this->createForm('AppBundle\Form\AreaFilterType', $filterData);
        //         $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
        //     }
        // }

        return array($filterForm, $queryBuilder);

    }

     /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, Request $request){
        //Sorting
        $sortCol = $queryBuilder->getRootAlias().'.'.$request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'asc'));

        //Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show' , 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }

        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request)
        {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('viewAreas', $requestParams);
        };

        // Paginator - view
        $view = new TwitterBootstrap4View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => '<<',
            'next_message' => '>>',
        ));

        return array($entities, $pagerHtml);

    }

     
    /*
     * Calculates the total of records string
     */
    protected function getTotalOfRecordsString($queryBuilder, $request) {
        $totalOfRecords = $queryBuilder->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $show = $request->get('pcg_show', 10);
        $page = $request->get('pcg_page', 1);

        $startRecord = ($show * ($page - 1)) + 1;
        $endRecord = $show * $page;

        if ($endRecord > $totalOfRecords) {
            $endRecord = $totalOfRecords;
        }
        return "Mostrando $startRecord - $endRecord de $totalOfRecords reg.";
    }


    /**
    * Bulk Action
    * @Route("/bulk-action/", name="area_bulk_action")
    * @Method({"GET", "POST"})
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Areas');

                foreach ($ids as $id) {
                    $folder = $repository->find($id);
                    $em->remove($folder);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'embalajeContadors was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the embalajeContadors ');
            }
        }

        return $this->redirect($this->generateUrl('viewAreas'));
    }

    public function getNombreAreas(){
        $entityManager = $this->getDoctrine()->getManager();

        $areas = $entityManager->getRepository(Areas::class)->findBy(array(), array('nomArea' => 'ASC'));
        $arrayOptions = [];

        //Armo el array directamente con los strings para no utilizar posiciones
        foreach($areas as $area){
            $arrayOptions[$area->getNomArea()] = $area->getNomArea();
        }
        
        return $arrayOptions;
    }

    public function findAreaByName($entityManager, $nombreArea){

        $query = $entityManager->createQuery(
            'SELECT a
            FROM AppBundle:Areas a
            WHERE trim(a.nomArea) = trim(:nomArea)'
        )->setParameter('nomArea', "$nombreArea");

        $area = $query->getResult();

        return $area;
    }

}