<?php
namespace App\Controller;

use App\Entity\Coincoins;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class TestDBController extends AbstractController
{
    #[Route('/test/coincoin', name: 'create_coincoin')]
    public function createCoincoin(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $coincoin = new Coincoins();
        $coincoin->setContent('Coincoin');
        $coincoin->setCreatedAt(new \DateTime());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($coincoin);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$coincoin->getId());
    }

    #[Route('/coincoin/{id}', name: 'coincoin_show')]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $package = new Package(new EmptyVersionStrategy());
        $img = $package->getUrl('/img/coin.webp');

        $coincoin = $doctrine->getRepository(Coincoins::class)->find($id);
        
        if (!$coincoin) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('<img src="'.$img.'" alt="Grapefruit slice atop a pile of other slices">Check out this great product: '.$coincoin->getContent());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }
}