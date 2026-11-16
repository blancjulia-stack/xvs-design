<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoryType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class CategorieController extends AbstractController
{
    // READ + CREATE : liste des catégories et formulaire d'ajout
    #[Route('/admin/category', name: 'admin.category.show')]
    public function index(Request $request, EntityManagerInterface $em, CategoriesRepository $repository): Response
    {
        $categorie = new Categories();
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('success', 'Catégorie « ' . $categorie->getCategorie() . ' » ajoutée.');

            return $this->redirectToRoute('admin.category.show');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Le formulaire contient des erreurs, rien n\'a été enregistré.');
        }

        return $this->render('admin/categorie.html.twig', [
            'categories' => $repository->findBy([], ['categorie' => 'ASC']),
            'form' => $form,
        ]);
    }

    // UPDATE
    #[Route('/admin/category/{id}/edit', name: 'category.edit', requirements: ['id' => '\d+'])]
    public function edit(Categories $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $categorie, ['submit_label' => 'Enregistrer']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Catégorie modifiée.');

            return $this->redirectToRoute('admin.category.show');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Le formulaire contient des erreurs, rien n\'a été enregistré.');
        }

        return $this->render('admin/taxonomy_edit.html.twig', [
            'titre' => 'Modifier la catégorie',
            'form' => $form,
            'retour' => $this->generateUrl('admin.category.show'),
        ]);
    }

    // DELETE
    #[Route('/admin/category/{id}/delete', name: 'category.delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Categories $categorie, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete-category-' . $categorie->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('danger', 'Jeton de sécurité invalide, suppression annulée.');

            return $this->redirectToRoute('admin.category.show');
        }

        // Une image doit obligatoirement avoir une catégorie : on bloque si la catégorie est encore utilisée.
        $nb = $categorie->getPortfolios()->count();
        if ($nb > 0) {
            $this->addFlash('danger', sprintf(
                'Impossible de supprimer « %s » : %d image(s) l\'utilisent encore. Change leur catégorie d\'abord.',
                $categorie->getCategorie(),
                $nb
            ));

            return $this->redirectToRoute('admin.category.show');
        }

        $em->remove($categorie);
        $em->flush();
        $this->addFlash('success', 'Catégorie supprimée.');

        return $this->redirectToRoute('admin.category.show');
    }
}
