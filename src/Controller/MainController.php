<?php

namespace App\Controller;

use App\Contact\Dto\Contact;
use App\Form\ContactType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index', methods: ['GET'])]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('main/index.html.twig', [
            'movies' => $repository->findBy([], ['id' => 'DESC'], 6),
        ]);
    }

    #[Route('/contact', name: 'app_main_contact', methods: ['GET'])]
    public function contact(): Response
    {
        $dto = new Contact();
        $form = $this->createForm(ContactType::class, $dto);

        $dto->setCreatedAt(new \DateTimeImmutable());

        return $this->render('main/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
