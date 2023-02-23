<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        //TODO Récupérer la liste des wish en BDD et le renvoyer
        return $this->render('wish/list.html.twig');
    }



     #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
     public function detail($id): Response
     {
         dump($id);
         //TODO Récupérer le détail du voeux en BDD et le renvoyer

         $wishList = ['Prout1', 'Prout2', 'Prout3', 'Prout4', 'Prout5', 'Prout6'];
         return $this->render('wish/detail.html.twig', [
             "wish" => $wishList[$id]
         ]);
     }

}
