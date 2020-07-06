<?php

namespace App\Controller;

use App\Entity\Caja;
use App\Form\CajaType;
use App\Repository\CajaRepository;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/caja")
 */
class CajaController extends AbstractController
{
    /**
     * @Route("/", name="caja_index", methods={"GET","POST"})
     */
    public function index(CajaRepository $cajaRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $caja = new Caja();
        $caja->setIngreso(0);
        $caja->setEgreso(0);
        $caja->setFecha(new \DateTime('now'));
        $caja->setLlevaTicket(true);
        //$form = $this->createForm(CajaType::class, $caja);
        $form = $this->createFormBuilder($caja)
            ->add('ingreso', NumberType::class)
            ->add('llevaTicket', CheckboxType::class)->setRequired(false)
            ->add('fecha', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Guardar'])
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($caja);
            $entityManager->flush();

            return $this->redirectToRoute('caja_index');
        }

        $buscar = $request->query->get('buscar') ?? '';
        $desde = $request->query->get('desde') ?? date('Y-m-d 00:00:00');;
        $hasta = $request->query->get('hasta') ?? date('Y-m-d 23:59:59');;

        if($desde != '') {
            $desde = new \DateTime($desde);
            if ($hasta == '') {
                $hasta = new \DateTime();
            } else {
                $hasta = new \DateTime($hasta);
            }
        }

        $cajasQuery = $cajaRepository->findByCustom($buscar, $desde, $hasta);

        $ingresosConTicket = $cajaRepository->getIngresos($buscar, $desde, $hasta, true);
        $ingresosSinTicket = $cajaRepository->getIngresos($buscar, $desde, $hasta, false);

        $totalConTicket = $ingresosConTicket[0][1] ?? 0;
        $totalSinTicket = $ingresosSinTicket[0][1] ?? 0;

        $pagination = $paginator->paginate(
            $cajasQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        $desdeDP = ($desde != '') ? $desde->format('d/m/Y') : '';
        $hastaDP = ($hasta != '') ? $hasta->format('d/m/Y') : '';

        return $this->render('caja/index.html.twig', [
            'pagination' => $pagination,
            'caja' => $caja,
            'form' => $form->createView(),
            'buscar' => $buscar,
            'desde' => $desdeDP,
            'hasta' => $hastaDP,
            'ingresos' => $totalConTicket,
            'ingresosSinTicket' => $totalSinTicket,
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
        $form->add('save', SubmitType::class, ['label' => 'Guardar']);
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
