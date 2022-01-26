<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Areas;


/**
 * 
 * @Route("/area")
 */
class AreaController extends BaseController
{

    /**
     * @Route("/create", name="createArea")
     */
    public function createArea(Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Cargar Area", "createArea");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('area/create.html.twig');
    }

    /**
     * @Route("/view", name="viewAreas")
     */
    public function viewAreas(){
        $entityManager = $this->getDoctrine()->getManager();

        $areas = $entityManager->getRepository(Areas::class)->findAll();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Areas", "viewAreas");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('area/all.html.twig', array(
            'areas' => $areas
        ));
    }

    /**
     * @Route("/edit/{id}", name="editArea")
     */
    public function editArea(Request $request, $id){
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Editar Area - $id", "editArea");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('area/edit.html.twig');
    }

        /**
     * @Route("/{id}", name="showArea")
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
     */
    public function deleteArea(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();
        
        return $this->redirectToRoute('viewAreas');
    }
    
}