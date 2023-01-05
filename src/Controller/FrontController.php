<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ArtisteRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    #[Route('/inscription', name: 'app_subscribe')]
    public function subscribe(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $password = $user->getPassword(); // Je récupère le mdp du formulaire
            $passwordHashed = $hasher->hashPassword($user, $password); // J'utilise l'objet de la classe UserPasswordHasherInterface pour hasher mon mdp. Puis je le stock dans ma variable.
            $user->setPassword($passwordHashed); // Une fois le mdp hashé, je le stock dans mon objet user.

            $userRepository->save($user, TRUE);

            $this->addFlash("success", "Votre inscription a bien été prise en compte");

            return $this->redirectToRoute('app_index');

        }

        return $this->render('front/subscribe.html.twig', [
            'formUser' => $form
        ]);
    }

    #[Route('/artistes', name:'app_artistes')]
    public function allArtistes(ArtisteRepository $artisteRepository)
    {
        return $this->render('front/artiste/index.html.twig', [
            'artistes' => $artisteRepository->findAll()
        ]);
    }

    #[Route('/artiste/{id}', name:'app_artiste')]
    public function artiste(Artiste $artiste)
    {
        dump($artiste);
        return $this->render('front/artiste/artiste.html.twig', [
            'artiste' => $artiste
        ]);
    }

}
