<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Form\BurgerType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/burger")
 */
class BurgerController extends AbstractController
{
    /**
     * @Route("/", name="burger_index", methods={"GET"})
     */
    public function index(BurgerRepository $burgerRepository): Response
    {
        return $this->render('burger/index.html.twig', [
            'burgers' => $burgerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="burger_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image=$form->get("image")->getData();
            foreach($image as $images){
                $fichier=md5(uniqid()).".".$images->guessExtension();
                //$images->move($this->getParameter("images_directory"),$fichier);
                $burger->setImage($fichier);
            }
            $entityManager->persist($burger);
            $entityManager->flush();

            return $this->redirectToRoute('burger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('burger/new.html.twig', [
            'burger' => $burger,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="burger_show", methods={"GET"})
     */
    public function show(Burger $burger): Response
    {
        return $this->render('burger/show.html.twig', [
            'burger' => $burger,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="burger_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Burger $burger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('burger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('burger/edit.html.twig', [
            'burger' => $burger,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="burger_delete", methods={"POST"})
     */
    public function delete(Request $request, Burger $burger, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$burger->getId(), $request->request->get('_token'))) {
            $entityManager->remove($burger);
            $entityManager->flush();
        }

        return $this->redirectToRoute('burger_index', [], Response::HTTP_SEE_OTHER);
    }
}
