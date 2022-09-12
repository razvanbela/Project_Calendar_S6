<?php

namespace App\Controller;

use App\Entity\Locations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends MyAbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $db): Response
    {
        $locations = $db->getRepository(Locations::class)->findAll();
        return $this->isLoggedIn() ? $this->render('home/index.html.twig', ['locations' => $locations]) : $this->redirectToRoute('app_login');
    }
}
