<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Histarch;
use AppBundle\Entity\Lados;

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
        
}
