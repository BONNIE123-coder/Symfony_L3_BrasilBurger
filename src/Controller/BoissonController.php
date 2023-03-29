<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Form\BoissonType;
use App\Repository\BoissonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boisson")
 */
class BoissonController extends AbstractController
{
    /**
     * @Route("/", name="boisson_index", methods={"GET"})
     */
    public function index(BoissonRepository $boissonRepository): Response
    {
        return $this->render('boisson/index.html.twig', [
            'boissons' => $boissonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="boisson_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $boisson = new Boisson();
        $form = $this->createForm(BoissonType::class, $boisson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image=$form->get("image")->getData();
            foreach($image as $images){
                $fichier=md5(uniqid()).".".$images->guessExtension();
                //$images->move($this->getParameter("images_directory"),$fichier);
                $boisson->setImage($fichier);
            }
            $entityManager->persist($boisson);
            $entityManager->flush();

            return $this->redirectToRoute('boisson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boisson/new.html.twig', [
            'boisson' => $boisson,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="boisson_show", methods={"GET"})
     */
    public function show(Boisson $boisson): Response
    {
        return $this->render('boisson/show.html.twig', [
            'boisson' => $boisson,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="boisson_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Boisson $boisson, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BoissonType::class, $boisson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('boisson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boisson/edit.html.twig', [
            'boisson' => $boisson,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="boisson_delete", methods={"POST"})
     */
    public function delete(Request $request, Boisson $boisson, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boisson->getId(), $request->request->get('_token'))) {
            $entityManager->remove($boisson);
            $entityManager->flush();
        }

        return $this->redirectToRoute('boisson_index', [], Response::HTTP_SEE_OTHER);
    }
}
