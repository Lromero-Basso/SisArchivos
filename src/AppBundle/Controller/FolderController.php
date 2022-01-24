<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FolderController extends BaseController
{
    /**
     * @Route("/createFolder", name="createFolder")
     */
    public function createFolder(Request $request){

    }

    /**
     * @Route("/viewFolders", name="viewFolders")
     */
    public function viewFolders(Request $request){

    }

    /**
     * @Route("/editFolder/{id}", name="editFolder")
     */
    public function editFolder(Request $request){

    }

    /**
     * @Route("/editFolder/{id}", name="deleteFolder")
     */
    public function deleteFolder(Request $request){

    }
}