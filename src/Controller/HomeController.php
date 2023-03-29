<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
     /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
    return $this->render("home/index.html.twig",[
        'controller_name'=>'HomeController',
    ]);
    }

    
    /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {
    return $this->render("home/index.html.twig",[
        'controller_name'=>'HomeController',
    ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(): Response
    {
    return $this->render("security/login.html.twig",[
        'controller_name'=>'SecurityController',
    ]);
    }

    

    /**
     * @Route("/register", name="app_register")
     */
    public function register(): Response
    {
    return $this->render("registration/register.html.twig",[
        'controller_name'=>'RegistrationController',
    ]);
    } 

     /**
     * @Route("/cart", name="app_cart")
     */
    public function cart(): Response
    {
        return $this->render("cart/cart.html",[
            'controller_name'=>'HomeController',
        ]);
    }

   
     /**
     * @Route("/admin", name="admin")
     */
    public function admin(): Response
    {
        return $this->render("admin/index.html.twig",[
            'controller_name'=>'HomeController',
        ]);
    }

    
      
     /**
     * @Route("/liste", name="liste")
     */
    public function liste(): Response
    {
        return $this->render("commande/index.html.twig",[
            'controller_name'=>'HomeController',
        ]);
    }
 
     /**
     * @Route("/homeA", name="homeA")
     */
    public function homeA(): Response
    {
        return $this->render("Accueil/index.html.twig",[
            'controller_name'=>'HomeController',
        ]);
    }

    /**
     * @Route("/allMenu", name="allMenu")
     */
    public function allMenu(): Response
    {
        return $this->render("allMenu/index.html.twig",[
            'controller_name'=>'HomeController',
        ]);
    }
}
