<?php

namespace App\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


  /**
   * @Route("/", name="app_home")
   */
    public function homePage(): Response
    {
        return $this->render('pages/index.html.twig');
    }


    /**
     * @Route("/layout", name="app_layout")
     */
    public function getLayout(): Response
    {
        return $this->render('pages/layout.html.twig');
    }
}
