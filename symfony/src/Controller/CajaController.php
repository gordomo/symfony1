<?php

namespace App\Controller;

use App\Entity\Caja;
use App\Form\CajaType;
use App\Repository\CajaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/caja")
 */
class CajaController extends AbstractController
{
    /**
     * @Route("/", name="caja_index", methods={"GET"})
     */
    public function index(CajaRepository $cajaRepository): Response
    {
        return $this->render('caja/index.html.twig', [
            'cajas' => $cajaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="caja_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $caja = new Caja();
        $form = $this->createForm(CajaType::class, $caja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($caja);
            $entityManager->flush();

            return $this->redirectToRoute('caja_index');
        }

        return $this->render('caja/new.html.twig', [
            'caja' => $caja,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="caja_show", methods={"GET"})
     */
    public function show(Caja $caja): Response
    {
        return $this->render('caja/show.html.twig', [
            'caja' => $caja,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="caja_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Caja $caja): Response
    {
        $form = $this->createForm(CajaType::class, $caja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('caja_index');
        }

        return $this->render('caja/edit.html.twig', [
            'caja' => $caja,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="caja_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Caja $caja): Response
    {
        if ($this->isCsrfTokenValid('delete'.$caja->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($caja);
            $entityManager->flush();
        }

        return $this->redirectToRoute('caja_index');
    }
}
