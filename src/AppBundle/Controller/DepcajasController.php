<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Depcajas;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap4View;

//depcajas

/**
 * @Route("/box")
 */
class DepcajasController extends BaseController
{
    /**
     * @Route("/create", name="createBox")
     */
    public function createBox(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Cargar Caja", "createBox");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('box/create.html.twig');
    }

    /**
     * @Route("/view", name="viewBoxes")
     */
    public function viewBoxes(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Cajas", "viewBoxes");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $queryBuilder = $entityManager->getRepository('AppBundle:Depcajas')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($boxes, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('folder/all.html.twig', array(
            'boxes' => $boxes,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
        ));
    }

    /**
     * @Route("/edit/{id}", name="editBox")
     */
    public function editBox(Request $request, $id){
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Editar Caja - $id", "editBox");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('box/edit.html.twig');
    }

    
    /**
     * @Route("/{id}", name="showBox")
     */
    public function showBox(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Vista Previa");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");
        
        return $this->render('box/show.html.twig');
    }

    /**
     * @Route("/{id}", name="deleteBox")
     */
    public function deleteBox(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();
        
        return $this->redirectToRoute('viewBoxes');
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request){
        $session = $request->getSession();
        $filterForm = $this->createForm('AppBundle\Form\DepcajaFilterType');


        //Reset filter
        if($request->get('filter_action') == 'reset'){
            if($session->get('DepcajaControllerFilter') != null){
                //If null apply reset, is necessary because without the validate, this closed the session
                $session->remove('DepcajaControllerFilter');
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
                $session->set('DepcajaControllerFilter', $filterData);
            }
        }
        return array($filterForm, $queryBuilder);

    }

     /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, Request $request){
        //Sorting
        $sortCol = $queryBuilder->getRootAlias().'.'.$request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));

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
            return $me->generateUrl('viewBoxes', $requestParams);
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
    * @Route("/bulk-action/", name="depcaja_bulk_action")
    * @Method("POST")
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Area');

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

        return $this->redirect($this->generateUrl('viewBoxes'));
    }

}