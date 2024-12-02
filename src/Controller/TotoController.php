<?php

namespace App\Controller;

use App\Entity\Toto;
use App\Form\TotoType;
use App\Repository\TotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class TotoController extends AbstractController
{
    #[Route(name: 'app_toto_index', methods: ['GET'])]
    public function index(TotoRepository $totoRepository): Response
    {
        return $this->render('toto/index.html.twig', [
            'totos' => $totoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_toto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $toto = new Toto();
        $form = $this->createForm(TotoType::class, $toto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($toto);
            $entityManager->flush();

            return $this->redirectToRoute('app_toto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('toto/new.html.twig', [
            'toto' => $toto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_toto_show', methods: ['GET'])]
    public function show(Toto $toto): Response
    {
        return $this->render('toto/show.html.twig', [
            'toto' => $toto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_toto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Toto $toto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TotoType::class, $toto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_toto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('toto/edit.html.twig', [
            'toto' => $toto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_toto_delete', methods: ['POST'])]
    public function delete(Request $request, Toto $toto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$toto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($toto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_toto_index', [], Response::HTTP_SEE_OTHER);
    }
}
