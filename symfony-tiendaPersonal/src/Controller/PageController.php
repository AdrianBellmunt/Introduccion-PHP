<?php

namespace App\Controller;

use App\Entity\Team;
use App\Service\ProductsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
public function index(ProductsService $productsService): Response
{
    $products = $productsService->getProducts();
    return $this->render('page/index.html.twig', compact('products'));
}

    
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', []);
    }

    #[Route('/news', name: 'news')]
    public function news(): Response
    {
        return $this->render('page/news.html.twig', []);
    }

    public function teamTemplate(ManagerRegistry $doctrine): Response
{
    $repository = $doctrine->getRepository(Team::class);
    $team = $repository->findAll();
    return $this->render('partials/_team.html.twig',compact('team'));
}

}
