<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\UserRepository;
use App\Repository\WishRepository;
use App\Utils\Censurator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{

    #[Route('/list', name: 'list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findWishAndCategory();
//      $wishes = $wishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }



     #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
     public function detail($id, WishRepository $wishRepository): Response
     {
         dump($id);

         $wish = $wishRepository->find($id);


         return $this->render('wish/detail.html.twig', [
             "wish" => $wish
         ]);
     }



    #[Route('/add', name: 'add')]
    public function add(
        WishRepository $wishRepository,
        Request $request,
        Censurator $censurator
    ): Response
    {

        $wish = new Wish();

            $wish->setAuthor($this->getUser()->getUserIdentifier());



        //création d'une intance de form lié à une instance de wish
        $wishForm = $this->createForm(WishType::class, $wish);

        dump($wish);

        //méthode qui extrait les éléments du formulaire de la requête
        $wishForm->handleRequest($request);


        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            $wish->setTitle($censurator->purify($wish->getTitle()));

//            foreach ($wish as $clef => $valeur) {
//                if (is_string($valeur)) {
//                    $censurator->purify($valeur);
//                    $wish->set . $clef
//                }
//
//            }
            //sauvegarde en BDD la nouvelle série
            $wishRepository->save($wish, true);

            $this->addFlash("success", "Wish added !");

            dump($wish);

            //redirige vers la page de détail de la série
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }


        return $this->render('wish/add.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }


    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update($id, WishRepository $wishRepository, Request $request): Response
    {

        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException('Oops ! Wish not found !');
        }

        $wishForm = $this->createForm(WishType::class, $wish);

        //méthode qui extrait les éléments du formulaire de la requête
        $wishForm->handleRequest($request);


        dump($wish);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            //update en BDD le wish
            $wishRepository->save($wish, true);

            $this->addFlash("success", "Wish updated !");

            dump($wish);

            //redirige vers la page de détail de la série
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/update.html.twig', [
            'wish' => $wish,
            'wishForm' => $wishForm->createView()
        ]);
    }



}
