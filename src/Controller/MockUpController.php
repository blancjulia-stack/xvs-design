<?php

namespace App\Controller;
use App\Entity\Portfolio;
use App\Form\InsertType;
use App\Form\UpdateType;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\DomCrawler\Image;

final class MockUpController extends AbstractController
{
    

     #[Route('/mockup/{mockup}', name: 'mockup.cat')]
    public function mockUp(string $mockup, PortfolioRepository $repository ): Response
    {
       $mockUp =$repository->findByMockUp($mockup);
       
        return $this->render('/pages/mockup.html.twig', [
            'controller_name' => 'MockUpController',
            'mockUp'=> $mockUp
        ]);
    }
}
