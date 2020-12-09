<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\CrudInterface;
use App\Service\ProduitService;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Exception\ProduitServiceException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProduitService $produitService): Response
    { 
        try{
            $produits = $produitService->searchAll();

        }catch(ProduitServiceException $e){
            return $this->render('index/index.html.twig', [
                "produits" => [],
                "error" => $e->getCode(). ": " . $e->getMessage()
            ]);
        }
        return $this->render('index/index.html.twig', [
            'produits' => $produits,
            'error' => NULL
        ]);
    } 

        /**
     * @Route("/create", name="create")
     */

    public function create(Request $request, EntityManagerInterface $manager) : Response {

        try{
            $produits = new Produit();

            $form = $this->createForm(ProduitType::class, $produits);
    
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
    
                $manager->persist($produits);
                $manager->flush();
                return $this->redirect("/");
            }
        }
        catch(ProduitServiceException $e){
            return $this->render('index/index.html.twig', [
                "produits" => [],
                "error" => $e->getCode(). ": " . $e->getMessage()
            ]);
        }
        return $this->render('index/create.html.twig', [
            'form' => $form->createView(),
            'error' => NULL
        ]);
    }

        /**
     *@Route("/delete/{id}", name="delete") 
    */
    public function delete(int $id, CrudInterface $crud): Response
    {
        try{
            $produit = $crud->find($id);
            $crud->remove($produit);
            return $this->redirect("/");
        }
        catch(ProduitServiceException $e){
            return $this->render('index/index.html.twig', [
                "error" => $e->getCode(). ": " . $e->getMessage()
            ]);
        }
    }

        /**
     *@Route("/update/{id}", name="update")
     */
    public function update(int $id, CrudInterface $crud, Request $request): Response
    {
        $produits = $crud->find($id);

        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $crud->modify($produits,$produit);
            return $this->redirect("/");
        }


        return $this->render('index/update.html.twig', ['form'=> $form->createView(), "produits" => $produits]);
    }

        /**
     *@Route("/detail/{id}", name="detail") 
     */
    public function detail(int $id, CrudInterface $crud) : Response {
        $produits = $crud->find($id);
        return $this->render("index/detail.html.twig", [
            "detail" => "Affichage du détail du produit",
            "produits" => $produits
        ]);
    }

}  
