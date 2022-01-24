<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BoxController extends BaseController
{
    /**
     * @Route("/createBox", name="createBox")
     */
    public function createBox(Request $request){

    }

    /**
     * @Route("/viewBoxes", name="viewBoxes")
     */
    public function viewBoxes(Request $request){

    }

    /**
     * @Route("/editBox/{id}", name="editBox")
     */
    public function editBox(Request $request){

    }

    /**
     * @Route("/deleteBox/{id}", name="deleteBox")
     */
    public function deleteBox(Request $request){

    }

    
}