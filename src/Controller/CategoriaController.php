<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/categoria", name="app_categoria")
     */
    public function index()
    {
        return $this->render('categoria/index.html.twig', [
            'controller_name' => 'CategoriaController',
        ]);
    }
}
