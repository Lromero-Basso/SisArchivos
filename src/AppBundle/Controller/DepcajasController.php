<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Depcajas;
use AppBundle\Entity\Areas;

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
     * @Method({"GET", "POST"})
     */
    public function createBox(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Nueva Caja", "createBox");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $now = new \DateTime(null, new \DateTimeZone('America/Argentina/Buenos_Aires'));

        $formBox = $request->get("Depcaja");

        $areas = $entityManager->getRepository(Areas::class)->findAll();

        if($formBox != null){
           
            $box = new Depcajas();
            
            $box->setCodEstante($formBox['codEstante']);
            $box->setCodLado($formBox['codLado']);
            $box->setPiso($formBox['piso']);
            $box->setCodArea($formBox['codArea']);
            $box->setColumna($formBox['columna']);
            $box->setTituloCaja($formBox['tituloCaja']);
            $box->setNroDesdeCaja($formBox['nroDesde']);
            $box->setNroHastaCaja($formBox['nroHasta']);
            $box->setObserva($formBox['observa']);
            $box->setEstado(0); //0 Porque hace referencia al estado VIGENTE
            $box->setFechaDesdeCaja(new \DateTime($formBox['fechaDesde']));
            $box->setArchivadoHasta(new \DateTime($formBox['archivadoHasta']));

            $entityManager->persist($box);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                '¡Se cargó correctamente la caja ID N° ' . $box->getId() . '!'
            );
            
            return $this->redirectToRoute('viewBoxes');
    
        }

        return $this->render('box/create.html.twig', array(
            'areas'     => $areas,
            'now'       => $now
        ));
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

        $this->modificarAutomaticamenteEstadoCaja($entityManager, $boxes);

        return $this->render('box/all.html.twig', array(
            'boxes' => $boxes,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
        ));
    }

    /**
     * @Route("/edit/{id}", name="editBox")
     * @Method({"GET", "POST"})
     */
    public function editBox(Request $request, $id){
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Editar Caja - $id", "editBox");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $countBox = count($entityManager->getRepository(Depcajas::class)->findAll());

        $actualDate = new \DateTime(null, new \DateTimeZone('America/Argentina/Buenos_Aires'));

        $box = $entityManager->getRepository(Depcajas::class)->findOneBy(array('id' => $id));
   
        $formBox = $request->get("Depcaja");

        $areas = $entityManager->getRepository(Areas::class)->findAll();

        if($formBox != null){

            $box->setCodEstante($formBox['codEstante']);
            $box->setCodLado($formBox['codLado']);
            $box->setPiso($formBox['piso']);
            $box->setCodArea($formBox['codArea']);
            $box->setColumna($formBox['columna']);
            $box->setTituloCaja($formBox['tituloCaja']);
            $box->setNroDesdeCaja($formBox['nroDesde']);
            $box->setNroHastaCaja($formBox['nroHasta']);
            $box->setObserva($formBox['observa']);
            $box->setFechaDesdeCaja(new \DateTime($formBox['fechaDesde']));
            $box->setArchivadoHasta(new \DateTime($formBox['archivadoHasta']));

            if($formBox['estado'] == "SI"){                
                $box->setEstado(2);
            }
            else{
                if($box->getArchivadoHasta() < $actualDate){
                    $box->setEstado(1);
                }
                else{
                    $box->setEstado(0);
                }
            }


            $entityManager->persist($box);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                '¡Se actualizó correctamente la caja ID N° ' . $box->getId() . '!'
            );

            return $this->redirectToRoute('viewBoxes');
        }


        return $this->render('box/create.html.twig', array(
            'countBox'  => $countBox,
            'box'       => $box,
            'areas'     => $areas
        ));
    }

    /**
     * @Route("/{id}", name="deleteBox")
     * @Method({"GET", "POST"})
     */
    public function deleteBox(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        // $entityManager->getConnection()->beginTransaction();

        $box = $entityManager->getRepository(Depcajas::class)->findOneBy(array('id'=>$id));

        try{
            $entityManager->remove($box);
            $entityManager->flush();
            // $entityManager->getConnection()->commit();

            return new JsonResponse(['success' => true]);

        }catch(\Exception $e){
            return new JsonResponse(['success' => false]);
        }
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request){
        $session = $request->getSession();
        $filterForm = $this->createForm('AppBundle\Form\DepcajaFilterType', $this->getTituloCaja());

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
        //Este else me deja el filtrado puesto por mas que me vaya a otro lado  
        else{
            if($session->has('DepcajaControllerFilter')){
                $filterData = $session->get('DepcajaControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('AppBundle\Form\DepcajaFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
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
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'DESC'));

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
    * @Method({"GET", "POST"})
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Depcaja');

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

    public function getTituloCaja(){
        $entityManager = $this->getDoctrine()->getManager();

        $depcajasTitulos    = $entityManager->getRepository(Depcajas::class)->findBy(array(), array('tituloCaja' => 'ASC'));
        $areas              = $entityManager->getRepository(Areas::class)->findBy(array(), array('id' => 'ASC'));

        $arrayOptions = [];
        $arrayTitulos = [];
        $arrayCodigoArea = [];
        $i = 0;

        foreach($depcajasTitulos as $depcaja){
            $arrayTitulos[$depcaja->getTituloCaja()] = $depcaja->getTituloCaja();
        }   
        foreach($areas as $area){
            $i = $area->getId();
            $arrayCodigoArea[$i." - ".$area->getNomArea()] = $area->getId();
        } 

        array_push($arrayOptions, $arrayTitulos, $arrayCodigoArea);
       
        return $arrayOptions;
        
    }

    public function modificarAutomaticamenteEstadoCaja($entityManager, $boxes){
        $actualDate = new \DateTime(null, new \DateTimeZone('America/Argentina/Buenos_Aires'));
        foreach($boxes as $box){
            //Comparo que la fecha de archivado hasta sea menor a la fecha actual y se setee solo a destruida solo si el estado es vigente o perdida
            if($box->getEstado() < 2){
                if($box->getArchivadoHasta() < $actualDate){             
                    $box->setEstado(1); //La seteo a destruida
                    $entityManager->persist($box);
                    $entityManager->flush();
                }
            }
            
        }
    }

}