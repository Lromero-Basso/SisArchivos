<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Empleados;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap4View;



/**
 * 
 * @Route("/empleados")
 */
class EmpleadosController extends BaseController
{
    /**
     * @Route("/view", name="viewEmpleados")
     * @Method({"GET", "POST"})
     */
    public function verEmpleados(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("Ver Empleados", "viewBoxes");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        $empleados = $entityManager->getRepository(Empleados::class)->findAll();

        return $this->render('empleados/default.html.twig', array(
            'empleados'=>$empleados
        ));
    }
}