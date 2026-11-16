<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\SousCategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

// API publique en LECTURE SEULE (aucune écriture possible ici : tout se fait via l'administration).
final class ApiController extends AbstractController
{
    #[Route('/api/categories', name: 'api.categories', methods: ['GET'])]
    public function categories(CategoriesRepository $repository): JsonResponse
    {
        $data = [];
        foreach ($repository->findBy([], ['categorie' => 'ASC']) as $categorie) {
            $data[] = [
                'id' => $categorie->getId(),
                'nom' => $categorie->getCategorie(),
                'nbImages' => $categorie->getPortfolios()->count(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/sous-categories', name: 'api.souscategories', methods: ['GET'])]
    public function sousCategories(SousCategoriesRepository $repository): JsonResponse
    {
        $data = [];
        foreach ($repository->findBy([], ['type' => 'ASC']) as $sousCategorie) {
            $data[] = [
                'id' => $sousCategorie->getId(),
                'nom' => $sousCategorie->getType(),
                'nbImages' => $sousCategorie->getPortfolios()->count(),
            ];
        }

        return $this->json($data);
    }
}
