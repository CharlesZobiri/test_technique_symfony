<?php

namespace App\Controller;


use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategorieController extends AbstractController
{
    #[Route('/api/categorie', name: 'get_categories', methods: ['GET'])]
    public function getCategories(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        $categoriesData = array_map(fn($categorie) => $categorie->toArray(), $categories);

        return $this->json($categoriesData);
    }
    

    #[Route('/api/categorie/create', name: 'create_categorie', methods: ['POST'])]
    public function createCategorie(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['nom'])) {
            return $this->json(['error' => 'Nom de la catégorie manquant'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $categorie = new Categorie();
        $categorie->setNom($data['nom']);

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['error' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($categorie);
        $entityManager->flush();

        return $this->json([
            'message' => 'Catégorie créée avec succès',
            'catégorie' => $categorie->toArray()
        ], JsonResponse::HTTP_CREATED);
    }


    #[Route('api/categorie/update/{id}', name: 'update_categorie', methods: ['PUT'])]
    public function updateCategorie(int $id, Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['nom'])) {
            return $this->json(['error' => 'Nom de la catégorie manquant ou données invalides'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $categorie = $categorieRepository->find($id);
        if (!$categorie) {
            return $this->json(['error' => 'Catégorie non trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        $categorie->setNom($data['nom']);

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }


        $entityManager->persist($categorie);
        $entityManager->flush();

        return $this->json([
            'message' => 'Catégorie mise à jour avec succès',
            'catégorie' => $categorie->toArray(),
        ], JsonResponse::HTTP_OK);
    }


    #[Route('api/categorie/delete/{id}', name: 'delete_categorie', methods: ['DELETE'])]
    public function deleteCategorie(int $id, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): JsonResponse
    {
        $categorie = $categorieRepository->find($id);
        if (!$categorie) {
            return $this->json(['error' => 'Catégorie non trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->json([
            'message' => 'Catégorie supprimée avec succès',
        ], JsonResponse::HTTP_OK);
    }
}
