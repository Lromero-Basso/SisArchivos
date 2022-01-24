<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {   

        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/hcaja", name="hcaja")
     */
    public function taletale(Request $request)
    { 
        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addRouteItem("HCaja", "hcaja");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");

        return $this->render('default/example.html.twig');
    }
        
}
