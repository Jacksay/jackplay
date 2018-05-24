<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller{

  /**
   * @Route("/", name="home")
   */
  public function home(){
    return $this->render('home.html.twig', array());
  }

  /**
   * @Route("/a-propos.html", name="about")
   */
  public function about(){
    return $this->render('about.html.twig', array());
  }
}
