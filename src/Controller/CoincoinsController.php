<?php

namespace App\Controller;

use App\Entity\Coincoins;
use App\Form\CoincoinsType;
use App\Repository\CoincoinsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coincoins')]
class CoincoinsController extends AbstractController
{
    #[Route('/', name: 'app_coincoins_index', methods: ['GET'])]
    public function index(CoincoinsRepository $coincoinsRepository): Response
    {
        return $this->render('coincoins/index.html.twig', [
            'coincoins' => $coincoinsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_coincoins_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoincoinsRepository $coincoinsRepository): Response
    {
        $coincoin = new Coincoins();
        $coincoin-> setCreatedAt(new \DateTime);
        $form = $this->createForm(CoincoinsType::class, $coincoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coincoinsRepository->save($coincoin, true);

            return $this->redirectToRoute('app_coincoins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coincoins/new.html.twig', [
            'coincoin' => $coincoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coincoins_show', methods: ['GET'])]
    public function show(Coincoins $coincoin): Response
    {
        return $this->render('coincoins/show.html.twig', [
            'coincoin' => $coincoin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coincoins_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coincoins $coincoin, CoincoinsRepository $coincoinsRepository): Response
    {
        $form = $this->createForm(CoincoinsType::class, $coincoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coincoinsRepository->save($coincoin, true);

            return $this->redirectToRoute('app_coincoins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coincoins/edit.html.twig', [
            'coincoin' => $coincoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coincoins_delete', methods: ['POST'])]
    public function delete(Request $request, Coincoins $coincoin, CoincoinsRepository $coincoinsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coincoin->getId(), $request->request->get('_token'))) {
            $coincoinsRepository->remove($coincoin, true);
        }

        return $this->redirectToRoute('app_coincoins_index', [], Response::HTTP_SEE_OTHER);
    }
}
