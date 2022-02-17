<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Handler\AreaHandler;
use AppBundle\Handler\DepcajasHandler;
use AppBundle\Handler\CarpecajaHandler;
use AppBundle\Handler\HistarchHandler;

class BaseController extends Controller
{
    public function getHandler($handlerName){

        switch($handlerName){
            case "AreaHandler":
                $handler = $this->get(AreaHandler::class);
                break;
            case "DepcajasHandler":
                $handler = $this->get(DepcajasHandler::class);
                break;
            case "CarpecajaHandler":
                $handler = $this->get(CarpecajaHandler::class);
                break;
            case "HistarchHandler":
                $handler = $this->get(HistarchHandler::class);
                break;
        }

        return $handler;
    }


    public function getEm(){
        return $this->getDoctrine()->getManager();
    }

    public function setBreadCrumbs($title, $routeName){
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        $breadcrumbs->addRouteItem($title, $routeName);
             
        $breadcrumbs->prependRouteItem("Inicio", "homepage");
    }

    public function setBreadCrumbsWithId($title, $routeName, $id){
        $breadcrumbs = $this->get("white_october_breadcrumbs");
             
        $breadcrumbs->addItem("$title - $id", "$routeName");
        
        $breadcrumbs->prependRouteItem("Inicio", "homepage");
    }
}