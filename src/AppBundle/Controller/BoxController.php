<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Depcajas;

//depcajas

/**
 * @Route("/box")
 */
class BoxController extends BaseController
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

        $boxes = $entityManager->getRepository(Depcajas::class)->findAll();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Cajas", "viewBoxes");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('box/all.html.twig', array(
            'boxes' => $boxes
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

}