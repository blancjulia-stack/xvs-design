<?php

namespace App\Controller;

use App\Entity\SousCategories;
use App\Form\SousCategoryType;
use App\Repository\SousCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class SousCategorieController extends AbstractController
{
    // READ + CREATE : liste des sous-catégories et formulaire d'ajout
    #[Route('/admin/sous-category', name: 'admin.souscategory.show')]
    public function index(Request $request, EntityManagerInterface $em, SousCategoriesRepository $repository): Response
    {
        $sousCategorie = new SousCategories();
        $form = $this->createForm(SousCategoryType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($sousCategorie);
            $em->flush();
            $this->addFlash('success', 'Sous-catégorie « ' . $sousCategorie->getType() . ' » ajoutée.');

            return $this->redirectToRoute('admin.souscategory.show');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Le formulaire contient des erreurs, rien n\'a été enregistré.');
        }

        return $this->render('admin/sous_categorie.html.twig', [
            'sousCategories' => $repository->findBy([], ['type' => 'ASC']),
            'form' => $form,
        ]);
    }

    // UPDATE
    #[Route('/admin/sous-category/{id}/edit', name: 'souscategory.edit', requirements: ['id' => '\d+'])]
    public function edit(SousCategories $sousCategorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SousCategoryType::class, $sousCategorie, ['submit_label' => 'Enregistrer']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Sous-catégorie modifiée.');

            return $this->redirectToRoute('admin.souscategory.show');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Le formulaire contient des erreurs, rien n\'a été enregistré.');
        }

        return $this->render('admin/taxonomy_edit.html.twig', [
            'titre' => 'Modifier la sous-catégorie',
            'form' => $form,
            'retour' => $this->generateUrl('admin.souscategory.show'),
        ]);
    }

    // DELETE
    #[Route('/admin/sous-category/{id}/delete', name: 'souscategory.delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(SousCategories $sousCategorie, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete-souscategory-' . $sousCategorie->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('danger', 'Jeton de sécurité invalide, suppression annulée.');

            return $this->redirectToRoute('admin.souscategory.show');
        }

        // Le lien image <-> sous-catégorie est porté par Portfolio : on détache d'abord les images
        // (elles ne sont PAS supprimées), sinon la contrainte de clé étrangère bloquerait la suppression.
        $nb = $sousCategorie->getPortfolios()->count();
        foreach ($sousCategorie->getPortfolios()->toArray() as $portfolio) {
            $sousCategorie->removePortfolio($portfolio);
        }

        $em->remove($sousCategorie);
        $em->flush();
        $this->addFlash('success', $nb > 0
            ? sprintf('Sous-catégorie supprimée (retirée de %d image(s)).', $nb)
            : 'Sous-catégorie supprimée.');

        return $this->redirectToRoute('admin.souscategory.show');
    }
}
