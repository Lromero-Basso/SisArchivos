<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Carpecaja;

//carpecaja

/**
 * @Route("/folder")
 */
class FolderController extends BaseController
{
    /**
     * @Route("/create", name="createFolder")
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
     */
    public function viewFolders(Request $request){

        $entityManager = $this->getDoctrine()->getManager();
      
        $folders = $entityManager->getRepository(Carpecaja::class)->findAll();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Carpetas", "viewFolders");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('folder/all.html.twig', array(
            'folders' => $folders
        ));
    }

    /**
     * @Route("/edit/{id}", name="editFolder")
     */
    public function editFolder(Request $request, $id){

        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("Editar Carpeta - $id", "editFolder");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");
        
        return $this->render('folder/edit.html.twig');
    }

}