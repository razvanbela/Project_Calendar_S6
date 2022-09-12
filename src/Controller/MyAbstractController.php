<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyAbstractController extends AbstractController
{
    public function isLoggedIn()
    {
        return $this->getUser() !== null;
    }
}
