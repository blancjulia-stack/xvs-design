<?php


namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\ImagesType;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{

    #[Route('/admin', name: 'admin.edit')]
    
    public function index(PortfolioRepository $repository): Response
    {
       
        $Images = $repository->findByOrdered();

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'HomeController',
            'Images' => $Images
        ]);
    }


   

    
    //==================images filtrées par type de mockup (même page d'administration)=========================
    #[Route('/admin/mockup/{mockUp}', name: 'admin.mockup.show')]
    public function mockups(string $mockUp, PortfolioRepository $repository): Response
    {
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'HomeController',
            'mockup' => $mockUp,
            'Images' => $repository->findByMockUp($mockUp),
        ]);
    }

    //==================route insert=========================
    #[Route('/admin/insert', name: 'insert.edit')]
    public function insert(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/image-site/')] string $imagesDirectory
    ): Response {
        $imageInsert = new Portfolio();
        $form = $this->createForm(ImagesType::class, $imageInsert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                    $imageInsert->setChemin('image-site/' . $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }



            $em->persist($imageInsert);   // nouvelle entité : persist obligatoire
            $em->flush();                 // écrit en base
            $this->addFlash('success', 'Bien enregistré!');

            return $this->redirectToRoute('admin.edit');
        }

        return $this->render('admin/insert.html.twig', [
            'form' => $form,
        ]);
    }
    //========================route update ==============================
    #[Route('/admin/update/{name}', name: 'update.edit')]

    public function update(string $name, PortfolioRepository $repository, Request $request, EntityManagerInterface $em): Response
    {
        // 1. On récupère l'image grâce à ta méthode du repository
        $image = $repository->findByName($name);
        // 2. Sécurité : si l'image n'existe pas, on renvoie une erreur 404 propre
        if (!$image) {
            throw $this->createNotFoundException("L'image avec le nom {$name} n'existe pas.");
        }
        // 3. On crée le formulaire en lui passant le formulaire qu on veut utiliser en premier parametre et en second parametre les données qui vont remplir le formulaire( donc pas de deuxieme argument quand on cree un formulaire d insertion, ici les données de l entite)
        $formUpdate = $this->createForm(ImagesType::class, $image);

        $formUpdate->handleRequest($request);   // lit les données envoyées

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $em->flush();   // pas de persist : l'entité est déjà suivie par Doctrine
            $this->addFlash('success', 'Bien modifié!');
            return $this->redirectAfterChange($image->getMockUp()?->getType());
        }

        return $this->render('admin/update.html.twig', [
            'image' => $image,
            'formUpdate' => $formUpdate,
        ]);
    }
    //========================route delete ==============================
    #[Route('/admin/delete/{name}', name: 'delete.edit', methods: ['DELETE'])]
    public function delete(string $name, PortfolioRepository $repository, EntityManagerInterface $em): Response
    {
        // 1. On récupère l'image grâce à ta méthode du repository
        $image = $repository->findByName($name);
        // 2. Sécurité : si l'image n'existe pas, on renvoie une erreur 404 propre
        if (!$image) {
            throw $this->createNotFoundException("L'image  n'existe pas.");
        }

        $mockUpType = $image->getMockUp()?->getType();

        $em->remove($image);
        $em->flush();
        $this->addFlash('success', 'L\'image a bien été supprimée.');

        return $this->redirectAfterChange($mockUpType);
       
    }

    // Revient sur la liste mockup d'origine si l'image en fait partie, sinon sur l'administration générale.
    private function redirectAfterChange(?string $mockUpType): Response
    {
        if ($mockUpType && $mockUpType !== 'non') {
            return $this->redirectToRoute('admin.mockup.show', ['mockUp' => $mockUpType]);
        }

        return $this->redirectToRoute('admin.edit');
    }
}
