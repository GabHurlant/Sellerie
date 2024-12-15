<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('home/index.html.twig', [
            'email' => $user ? $user->getEmail() : null,
        ]);
    }
}

