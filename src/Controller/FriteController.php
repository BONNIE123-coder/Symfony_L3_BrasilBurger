<?php

namespace App\Controller;

use App\Entity\Frite;
use App\Form\FriteType;
use App\Repository\FriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/frite")
 */
class FriteController extends AbstractController
{
    /**
     * @Route("/", name="frite_index", methods={"GET"})
     */
    public function index(FriteRepository $friteRepository): Response
    {
        return $this->render('frite/index.html.twig', [
            'frites' => $friteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="frite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $frite = new Frite();
        $form = $this->createForm(FriteType::class, $frite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image=$form->get("image")->getData();
            foreach($image as $images){
                $fichier=md5(uniqid()).".".$images->guessExtension();
                //$images->move($this->getParameter("images_directory"),$fichier);
                $frite->setImage($fichier);
            }
            $entityManager->persist($frite);
            $entityManager->flush();

            return $this->redirectToRoute('frite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frite/new.html.twig', [
            'frite' => $frite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="frite_show", methods={"GET"})
     */
    public function show(Frite $frite): Response
    {
        return $this->render('frite/show.html.twig', [
            'frite' => $frite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="frite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Frite $frite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FriteType::class, $frite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('frite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frite/edit.html.twig', [
            'frite' => $frite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="frite_delete", methods={"POST"})
     */
    public function delete(Request $request, Frite $frite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$frite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($frite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('frite_index', [], Response::HTTP_SEE_OTHER);
    }
}
