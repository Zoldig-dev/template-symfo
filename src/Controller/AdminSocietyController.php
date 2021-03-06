<?php

namespace App\Controller;

use App\Entity\Society;
use App\Form\SocietyType;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin_society")
 */
class AdminSocietyController extends AbstractController
{
    /**
     * @Route("/", name="society_index", methods={"GET"})
     */
    public function index(SocietyRepository $societyRepository): Response
    {
        return $this->render('admin/admin_society/index.html.twig', [
            'societies' => $societyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="society_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $society = new Society();
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($society);
            $entityManager->flush();

            return $this->redirectToRoute('society_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_society/new.html.twig', [
            'admin_society' => $society,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="society_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Society $society): Response
    {
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('society_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_society/edit.html.twig', [
            'admin_society' => $society,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="society_delete", methods={"POST"})
     */
    public function delete(Request $request, Society $society): Response
    {
        if ($this->isCsrfTokenValid('delete'.$society->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($society);
            $entityManager->flush();
        }

        return $this->redirectToRoute('society_index', [], Response::HTTP_SEE_OTHER);
    }
}
