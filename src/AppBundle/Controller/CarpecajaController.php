<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Carpecaja;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap4View;


//carpecaja

/**
 * @Route("/folder")
 */
class CarpecajaController extends BaseController
{
    /**
     * @Route("/create", name="createFolder")
     * @Method({"GET", "POST"})
     */
    public function createFolder(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Cargar Carpeta", "createFolder");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('folder/create.html.twig');
    }

    /**
     * @Route("/view", name="viewFolders")
     * @Method("GET")
     */
    public function viewFolders(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Carpetas", "viewFolders");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $queryBuilder = $entityManager->getRepository('AppBundle:Carpecaja')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($folders, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('folder/all.html.twig', array(
            'folders' => $folders,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
        ));
    }

    /**
     * @Route("/edit/{id}", name="editFolder")
     * @Method({"GET", "POST"})
     */
    public function editFolder(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Editar Carpeta - $id", "editFolder");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");
        
        return $this->render('folder/create.html.twig');
    }

    /**
     * @Route("/{id}", name="showFolder")
     * @Method({"GET", "POST"})
     */
    public function showFolder(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $folder = $entityManager->getRepository(Carpecaja::class)->findOneBy(array('id'=>$id));
        
        return $this->render('folder/show.html.twig', array(
            'folder' => $folder
        ));
    }

    /**
     * @Route("/{id}", name="deleteFolder")
     * @Method({"GET", "POST"})
     */
    public function deleteFolder(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();
        
        return $this->redirectToRoute('viewFolders');
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request){
        $session = $request->getSession();

     

        $filterForm = $this->createForm('AppBundle\Form\CarpecajaFilterType', $this->getDataCarpeta());

        //Reset filter
        if($request->get('filter_action') == 'reset'){
            if($session->get('CarpecajaControllerFilter') != null){
                //If null apply reset, is necessary because without the validate, this closed the session
                $session->remove('CarpecajaControllerFilter');
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
                   $session->set('CarpecajaControllerFilter', $filterData);
            }
        }
        //Este else me deja el filtrado puesto por mas que me vaya a otro lado
        // else{
        //     if($session->has('CarpecajaControllerFilter')){
        //         $filterData = $session->get('CarpecajaControllerFilter');

        //         foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
        //             if (is_object($filter)) {
        //                 $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
        //             }
        //         }

        //         $filterForm = $this->createForm('AppBundle\Form\CarpecajaFilterType', $filterData);
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
            return $me->generateUrl('viewFolders', $requestParams);
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
    * @Route("/bulk-action/", name="carpecaja_bulk_action")
    * @Method("POST")
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Carpecaja');

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

        return $this->redirect($this->generateUrl('viewFolders'));
    }

    public function getDataCarpeta(){
        $entityManager = $this->getDoctrine()->getManager();

        $carpecajasTitulo = $entityManager->getRepository(Carpecaja::class)->findBy(array(), array('tituloCarp' => 'ASC'));
        $carpecajasNro = $entityManager->getRepository(Carpecaja::class)->findBy(array(), array('nroCarpeta' => 'ASC'));

        //Como solo recibe un array la funcion del FORM, tengo que unirlos en uno nuevo
        
        $arrayOptions = [];
        $arrayTitulos = [];
        $arrayNroCarp = [];

        foreach($carpecajasTitulo as $carpecaja){
            $arrayTitulos[$carpecaja->getTituloCarp()] = $carpecaja->getTituloCarp();
        }

        foreach($carpecajasNro as $carpecaja){
            $arrayNroCarp[$carpecaja->getNroCarpeta()] = $carpecaja->getNroCarpeta();
        }

        array_push($arrayOptions, $arrayTitulos, $arrayNroCarp);


        return $arrayOptions;
        
    }

  

}