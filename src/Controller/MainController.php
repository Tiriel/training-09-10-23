<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController::index',
        ]);
    }

    #[Route('/contact', name: 'app_main_contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController::contact',
        ]);
    }
}
