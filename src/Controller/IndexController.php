<?php


namespace App\Controller;


use App\Repository\PortfolioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index( PortfolioRepository $repository ): Response
    {
       $Images =$repository->findByOrdered();
       
        return $this->render('pages/index.html.twig', [ 
            'controller_name' => 'HomeController',
            'Images'=> $Images
        ]);
    }
   

    //READ (pour index, mockup et administration)
   #[Route('/category/{category}', name: 'category.show')]
public function categorie(string $category, PortfolioRepository $repository): Response
{
    $Images = $repository->findByCategorie($category);
   
    return $this->render('pages/index.html.twig', [
        'controller_name' => 'HomeController',
        'Images' => $Images
    ]);
}
#[Route('/mockup', name: 'mockUp.show')]
    public function allMockUp(PortfolioRepository $repository): Response
    {
        $mockUp = $repository->findAllMockup();
        return $this->render('pages/mockup.html.twig', [
            'controller_name' => 'MockUpController',
            'mockUp'=> $mockUp
        ]);
    }

    }

