<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\JsonResponse; 

class ProduitController extends AbstractController
{
    #[Route('/api/produit', name: 'get_produits', methods: ['GET'])]
    public function getProduits(ProduitRepository $produitRepository): JsonResponse
    {   
        // Récupération de tous les produits donc $produit = tableau
        $produits = $produitRepository->findAll();
        $produitsData = array_map(fn($produit) => $produit->toArray(), $produits);

        return $this->json($produitsData);
    }


    #[Route('/api/produit/create', name: 'create_produit', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): JsonResponse
    {
        // Récupération des données de la requête $data = tableau
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['nom'], $data['description'], $data['prix'], $data['categorie'])) {
            return $this->json(['error' => 'Données invalides ou manquantes'], JsonResponse::HTTP_BAD_REQUEST);
        }        

        $produit = new Produit();
        $produit->setNom($data['nom']);
        $produit->setDescription($data['description']);
        $produit->setPrix((float)$data['prix']);
        $produit->setDateCreation(new \DateTime()); 

        // Trouver la catégorie
        $categorie = $categorieRepository->findOneBy(['nom' => $data['categorie']]);
        if (!$categorie) {
            return $this->json(['error' => 'Catégorie non trouvée'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $produit->setCategorie($categorie);

        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->json([
            'message' => 'Produit créé avec succès',
            'produit' => $produit->toArray()
        ], JsonResponse::HTTP_CREATED);
    }


    #[Route('/api/produit/update/{id}', name: 'upadta_produit', methods: ['PUT'])]
    public function updateProduit(int $id, Request $request, ProduitRepository $produitRepository, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager): JsonResponse 
    {
        $produit = $produitRepository->find($id);
        if (!$produit) {
            return $this->json(['error' => 'Produit non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Données invalides'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (isset($data['nom'])) {
            $produit->setNom($data['nom']);
        }

        if (isset($data['description'])) {
            $produit->setDescription($data['description']);
        }

        if (isset($data['prix'])) {
            $produit->setPrix((float)$data['prix']);
        }

        if (isset($data['categorie'])) {
            // Si une catégorie est précisée alors on recherche le nom dans la BDD
            $categorie = $categorieRepository->findOneBy(['nom' => $data['categorie']]);

            if (!$categorie) {
                return $this->json(['error' => 'Catégorie non trouvée'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $produit->setCategorie($categorie);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Produit modifié avec succès',
            'produit' => $produit->toArray()
        ], JsonResponse::HTTP_OK);

    }

}