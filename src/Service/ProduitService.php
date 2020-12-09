<?php

namespace App\Service;

use App\Entity\Produit;
use App\Service\CrudInterface;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;
use App\Service\Exception\ProduitServiceException;

class ProduitService implements CrudInterface{

    private $repository;
    private $manager;

    public function __construct(ProduitRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function searchAll()
    {
        try{
            $produits = $this->repository->findAll();
            return $produits;
        }catch(DriverException $e){
            throw new ProduitServiceException("Problème technique rencontré. Veuillez réeesayer.", $e->getCode());
        } 
    }

    public function remove($produits)
    {
        try{
            $this->manager->remove($produits);
            $this->manager->flush();
        }
        catch(DriverException $e){
            throw new ProduitServiceException("Problème technique rencontré. Veuillez réeesayer.", $e->getCode());
        }
    }


    public function find($id)
    {
        $produits = $this->repository->find($id);
        return $produits;
    }

    public function persist($produit)
    {
        $this->manager->persist($produit);
        $this->manager->flush();
    }

    public function update($produits, $produit)
    {
        $produits->setDesignation($produit->getDesignation())->setPrix($produit->getPrix());
        $this->manager->flush();
    }

    public function modify(Produit $produits, Produit $produit)
    {
        $produits->setDesignation($produit->getDesignation())->setPrix($produit->getPrix());
        $this->manager->flush();
    }
}
?>