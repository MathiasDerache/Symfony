<?php

namespace App\Service;


interface CrudInterface
{
    public function searchAll();
    public function find($id);
    public function remove($produits);
    public function update($produits, $produit);
    public function persist($produit);
}