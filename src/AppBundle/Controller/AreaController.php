<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AreaController extends BaseController
{

    /**
     * @Route("/createArea", name="createArea")
     */
    public function createArea(Request $request){

    }

    /**
     * @Route("/viewAreas", name="viewAreas")
     */
    public function viewAreas(Request $request){
        
    }

    /**
     * @Route("/editArea/{id}", name="editArea")
     */
    public function editArea(Request $request){
        
    }

    /**
     * @Route("/deleteArea/{id}", name="deleteArea")
     */
    public function deleteArea(Request $request){
        
    }
    
}