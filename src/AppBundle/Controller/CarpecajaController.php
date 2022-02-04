<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Carpecaja;
use AppBundle\Entity\Depcajas;
use AppBundle\Entity\Histarch;

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
             
        $breadcrumbs->addRouteItem("Nueva Carpeta", "createFolder");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $now = new \DateTime(null, new \DateTimeZone('America/Argentina/Buenos_Aires'));

        $boxes = $entityManager->getRepository(Depcajas::class)->findAll();
        
        $formFolder = $request->get("Carpecaja");

        if($formFolder != null){

            $folder = new Carpecaja();
            
            $folder->setNroCarpeta($formFolder['nroCarp']);
            $folder->setCodCaja($formFolder['codigoCaja']);
            $folder->setTituloCarp($formFolder['tituloCarp']);
            $folder->setNEstado(0); //Seteo a 0 porque equivale al estado "EN ARCHIVO"
            $folder->setFechaDesdeCarp(new \DateTime($formFolder['fechaDesde']));
            
            $entityManager->persist($folder);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                '¡Se cargó correctamente la carpeta ID N° ' . $folder->getId() . '!'
            );

            return $this->redirectToRoute('viewFolders');
            
        }

        return $this->render('folder/create.html.twig', array(
            'boxes' => $boxes,
            'now'   => $now
        ));
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

        $now = new \DateTime(null, new \DateTimeZone('America/Argentina/Buenos_Aires'));

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($folders, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('folder/all.html.twig', array(
            'folders'               => $folders,
            'pagerHtml'             => $pagerHtml,
            'filterForm'            => $filterForm->createView(),
            'totalOfRecordsString'  => $totalOfRecordsString,
            'now'                   => $now
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

        $folder = $entityManager->getRepository(Carpecaja::class)->findOneBy(array('id' => $id));

        $boxes = $entityManager->getRepository(Depcajas::class)->findAll();

        $formFolder = $request->get("Carpecaja");

        if($formFolder != null){

            $folder->setNroCarpeta($formFolder['nroCarp']);
            $folder->setCodCaja($formFolder['codigoCaja']);
            $folder->setTituloCarp($formFolder['tituloCarp']);
            $folder->setNEstado($formFolder['estado']);
            $folder->setFechaDesdeCarp(new \DateTime($formFolder['fechaDesde']));
            
            $entityManager->persist($folder);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                '¡Se actualizó correctamente la carpeta ID N° ' . $folder->getId() . '!'
            );

            return $this->redirectToRoute('viewFolders');
            
        }
        
        return $this->render('folder/create.html.twig', array(
            'folder'    => $folder,
            'boxes' => $boxes
        ));
    }

    /**
     * @Route("/retire/{id}", name="retireFolder")
     * @Method({"GET", "POST"})
     */
    public function retireFolder(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $formHistarchRetire =  $request->get("HistarchRetire");

        $folder = $entityManager->getRepository(Carpecaja::class)->findOneBy(array('id' => $id));

        $now = new \DateTime(null, new \DateTimeZone('America/Argentina/Buenos_Aires'));

        if($formHistarchRetire != null){

            $histarch = new Histarch();
            $histarch -> setCodCarpeta($id);  
            $histarch -> setLegajo($formHistarchRetire['legajo']);
            $histarch -> setFechaRetiro(new \DateTime($formHistarchRetire['fechaRetiro']));
            $folder   -> setNEstado(1); //Seteo el estado a retirado

            $entityManager->persist($histarch);

            $entityManager->flush();
            
            $this->addFlash(
                'notice',
                '¡Se retiró correctamente la carpeta '. $folder->getId() .'!'
            );

            return $this->redirectToRoute('viewFolders');

        }
        
        return $this->render('modals/_retireFolderModal.html.twig', array(
            'now'   => $now
        ));
    }

    /**
     * @Route("/return/{id}", name="returnFolder")
     * @Method({"GET", "POST"})
     */
    public function returnFolder(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $formHistarchReturn =  $request->get("HistarchReturn");

        $folder = $entityManager->getRepository(Carpecaja::class)->findOneBy(array('id' => $id));

        $histarch = $entityManager->getRepository(Histarch::class)->findOneBy(array('codCarpeta'=>$id));

        $now = new \DateTime(null, new \DateTimeZone('America/Argentina/Buenos_Aires'));

        if($formHistarchReturn != null){

            $histarch -> setFechaDevolucion(new \DateTime($formHistarchReturn['fechaDevolucion']));
            $folder   -> setNEstado(0); //Seteo el estado a en archivo nuevamente

            $entityManager->persist($histarch);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                '¡Se devolvió correctamente la carpeta '. $folder->getId() .'!'
            );

            return $this->redirectToRoute('viewFolders');

        }
        
        return $this->render('modals/_returnFolderModal.html.twig', array(
            'now'       => $now,
            'legajo'    => $histarch->getLegajo()
        ));
    }
    
    /**
     * @Route("/delete/{id}", name="deleteFolder")
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
        else{
            if($session->has('CarpecajaControllerFilter')){
                $filterData = $session->get('CarpecajaControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('AppBundle\Form\CarpecajaFilterType', $filterData);
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

    public function getDataCarpeta(){
        $entityManager = $this->getDoctrine()->getManager();

        //Al select no mando el total de datos, mando solo los que tiene la entidad en la que estoy

        $carpeCajasTitulo   = $entityManager->getRepository(Carpecaja::class)->findBy(array(), array('tituloCarp' => 'ASC'));
        $carpeCajasNro      = $entityManager->getRepository(Carpecaja::class)->findBy(array(), array('nroCarpeta' => 'ASC'));
        $carpeCajascodCajas           = $entityManager->getRepository(Carpecaja::class)->findBy(array(), array('codCaja' => 'ASC'));
        //Como solo recibe un array la funcion del FORM, tengo que unirlos en uno nuevo

        $arrayOptions = [];
        $arrayTitulos = [];
        $arrayNroCarp = [];
        $arrayCodCaja = [];

        foreach($carpeCajasTitulo as $carpecaja){
            $arrayTitulos[$carpecaja->getTituloCarp()] = $carpecaja->getTituloCarp();
        }

        foreach($carpeCajasNro as $carpecaja){
            $arrayNroCarp[$carpecaja->getNroCarpeta()] = $carpecaja->getNroCarpeta();
        }

        foreach($carpeCajascodCajas as $codCaja){
            $arrayCodCaja[$codCaja->getCodCaja()] = $codCaja->getCodCaja();
        }

        array_push($arrayOptions, $arrayTitulos, $arrayNroCarp, $arrayCodCaja);

        return $arrayOptions;
        
    }

  

}