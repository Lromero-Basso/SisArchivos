<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Histarch;
use AppBundle\Entity\Carpecaja;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap4View;


/**
 * @Route("/record")
 */
class HistarchController extends BaseController
{
    /**
     * @Route("/create", name="createRecord")
     * @Method({"GET", "POST"})
     */
    public function createRecord(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Nuevo Registro", "createRecord");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $folders = $entityManager->getRepository(Carpecaja::class)->findAll();

        $formRecord = $request->get("Histarch");

        if($formRecord != null){

            $record = new Histarch();
            
            $record->setCodCarpeta($formRecord['codCarpeta']);
            $record->setLegajo($formRecord['legajo']);
            //Las fechas va de la mano del fitro de busqueda
            // $record->setFechaRetiro($formRecord['fechaDesde']);
            // $record->setFechaDevolucion($formRecord['fechaHasta']);
            

            $entityManager->persist($record);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                '¡Se cargó correctamente el registro ID N° ' . $record->getId() . '!'
            );
            
            return $this->redirectToRoute('viewRecords');

        }

        return $this->render('record/create.html.twig', array(
            'folders' => $folders
        ));
    }

    /**
     * @Route("/view", name="viewRecords")
     * @Method("GET")
     */
    public function viewRecords(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Registros", "viewRecords");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $queryBuilder = $entityManager->getRepository('AppBundle:Histarch')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($records, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('record/all.html.twig', array(
            'records' => $records,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
        ));
    }

    /**
     * @Route("/edit/{id}", name="editRecord")
     * @Method({"GET", "POST"})
     */
    public function editRecord(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Editar Registro - $id", "editRecord");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $record = $entityManager->getRepository(Histarch::class)->findOneBy(array('id'=>$id));

        $folders = $entityManager->getRepository(Carpecaja::class)->findAll();

        $formRecord = $request->get("Histarch");
        if($formRecord != null){

            $record->setCodCarpeta($formRecord['codCarpeta']);
            $record->setLegajo($formRecord['legajo']);
            //Las fechas va de la mano del fitro de busqueda
            // $record->setFechaRetiro($formRecord['fechaDesde']);
            // $record->setFechaDevolucion($formRecord['fechaHasta']);
            
            $entityManager->persist($record);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                '¡Se actualizó correctamente el registro ID N° ' . $record->getId() . '!'
            );
            
            return $this->redirectToRoute('viewRecords');

        }
        
        return $this->render('record/create.html.twig', array(
            'record' => $record,
            'folders' => $folders
        ));
    }

    /**
     * @Route("/{id}", name="showRecord")
     * @Method("GET")
     */
    public function showRecord(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Vista Previa");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");
        
        return $this->render('record/show.html.twig');
    }

    /**
     * @Route("/{id}", name="deleteRecord")
     * @Method({"GET", "POST"})
     */
    public function deleteRecord(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();
        
        return $this->redirectToRoute('viewRecords');
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request){
        $session = $request->getSession();
        $filterForm = $this->createForm('AppBundle\Form\HistarchFilterType');


        //Reset filter
        if($request->get('filter_action') == 'reset'){
            if($session->get('HistarchControllerFilter') != null){
                //If null apply reset, is necessary because without the validate, this closed the session
                $session->remove('HistarchControllerFilter');
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
                $session->set('HistarchControllerFilter', $filterData);
            }
        }
         //Este else me deja el filtrado puesto por mas que me vaya a otro lado
        // else{
        //     if($session->has('HistarchControllerFilter')){
        //         $filterData = $session->get('HistarchControllerFilter');

        //         foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
        //             if (is_object($filter)) {
        //                 $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
        //             }
        //         }

        //         $filterForm = $this->createForm('AppBundle\Form\HistarchFilterType', $filterData);
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
            return $me->generateUrl('viewRecords', $requestParams);
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
    * @Route("/bulk-action/", name="histarch_bulk_action")
    * @Method({"GET", "POST"})
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Histarch');

                foreach ($ids as $id) {
                    $record = $repository->find($id);
                    $em->remove($record);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'embalajeContadors was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the embalajeContadors ');
            }
        }

        return $this->redirect($this->generateUrl('viewRecords'));
    }

}