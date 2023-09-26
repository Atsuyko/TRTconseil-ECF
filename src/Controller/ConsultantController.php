<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidatureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConsultantController extends AbstractController
{
    #[Route('/consultant', name: 'app_consultant')]
    public function index(UserRepository $userRepository, AnnonceRepository $annonceRepository, CandidatureRepository $candidatureRepository): Response
    {
        $candidats = $userRepository->findBy(array('role' => 'candidat'));
        $userCandidats = [];

        foreach ($candidats as $candidat) {
            if ($candidat->getRoles() === ["ROLE_USER"]) {
                array_push($userCandidats, $candidat);
            }
        }

        $recruteurs = $userRepository->findBy(array('role' => 'recruteur'));
        $userRecruteurs = [];

        foreach ($recruteurs as $recruteur) {
            if ($recruteur->getRoles() === ["ROLE_USER"]) {
                array_push($userRecruteurs, $recruteur);
            }
        }

        $annonces = $annonceRepository->findAll();
        $recAnnonces = [];

        foreach ($annonces as $annonce) {
            if ($annonce->isIsValidate() === false) {
                array_push($recAnnonces, $annonce);
            }
        }

        $candidatures = $candidatureRepository->findAll();
        $canCandidatures = [];

        foreach ($candidatures as $candidature) {
            if ($candidature->isIsValidate() === false) {
                array_push($canCandidatures, $candidature);
            }
        }

        return $this->render('consultant/index.html.twig', [
            'userCandidats' => $userCandidats,
            'userRecruteurs' => $userRecruteurs,
            'recAnnonces' => $recAnnonces,
            'canCandidatures' => $canCandidatures,
        ]);
    }

    #[Route('/consultant/validate-candidat/{id}', name: 'consultant.validate.candidat', methods: ['GET', 'POST'])]
    public function validateCandidat(User $user, EntityManagerInterface $manager): Response
    {
        $user->setRoles(["ROLE_CANDIDAT"]);
        $user->setIsValidate(true);
        $manager->persist($user);
        $manager->flush();

        return $this->render('consultant/validate_candidat.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/consultant/reject-candidat/{id}', name: 'consultant.reject.candidat', methods: ['GET', 'POST'])]
    public function rejectCandidat(User $user, EntityManagerInterface $manager): Response
    {
        $manager->remove($user);
        $manager->flush();

        return $this->render('consultant/reject_candidat.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/consultant/validate-recruteur/{id}', name: 'consultant.validate.recruteur', methods: ['GET', 'POST'])]
    public function validateRecruteur(User $user, EntityManagerInterface $manager): Response
    {
        $user->setRoles(["ROLE_RECRUTEUR"]);
        $user->setIsValidate(true);
        $manager->persist($user);
        $manager->flush();

        return $this->render('consultant/validate_recruteur.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/consultant/reject-recruteur/{id}', name: 'consultant.reject.recruteur', methods: ['GET', 'POST'])]
    public function rejectRecruteur(User $user, EntityManagerInterface $manager): Response
    {
        $manager->remove($user);
        $manager->flush();

        return $this->render('consultant/reject_recruteur.html.twig', [
            'user' => $user,
        ]);
    }
}
