<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Candidature;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatRepository;
use App\Repository\CandidatureRepository;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{
    #[Route('/candidature-annonce-{id}', name: 'app_candidature')]
    public function index(CandidatureRepository $candidatureRepository, AnnonceRepository $annonceRepository, RecruteurRepository $recruteurRepository, int $id): Response
    {
        $currentUser = $this->getUser();
        $recruteurs = $recruteurRepository->findAll();

        foreach ($recruteurs as $recruteur) {
            if ($recruteur->getUser() === $currentUser) {
                $currentRecruteur = $recruteur;
            }
        }

        $annonce = $annonceRepository->findOneBy(['id' => $id]);

        if ($annonce->getRecruteur() === $currentRecruteur) {

            $candidatures = $candidatureRepository->findAll();
            $canCandidatures = [];

            foreach ($candidatures as $candidature) {
                if ($candidature->isIsValidate() === true and $candidature->getAnnonce() === $annonce) {
                    array_push($canCandidatures, $candidature);
                }
            }
            return $this->render('candidature/index.html.twig', [
                'canCandidatures' => $canCandidatures,
            ]);
        } else {

            return $this->redirectToRoute('app_annonce');
        }
    }

    #[Route('/candidature/new/{id}', name: 'candidature.new')]
    public function new(CandidatRepository $candidatRepository, AnnonceRepository $annonceRepository, EntityManagerInterface $manager, int $id): Response
    {
        $currentUser = $this->getUser();
        $candidats = $candidatRepository->findAll();
        foreach ($candidats as $candidat) {
            if ($candidat->getUser() === $currentUser) {
                $currentCandidat = $candidat;
            }
        }

        $currentAnnonce = $annonceRepository->findOneBy(['id' => $id]);

        $candidature = new Candidature();
        $candidature->setCandidat($currentCandidat);
        $candidature->setAnnonce($currentAnnonce);
        $candidature->setIsValidate(false);

        $manager->persist($candidature);
        $manager->flush();

        return $this->render('candidature/new.html.twig', [
            'annonce' => $currentAnnonce,
            'candidat' => $currentCandidat
        ]);
    }

    #[Route('/candidature/delete/{id}', 'candidature.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, CandidatureRepository $candidatureRepository, int $id): Response
    {
        $candidature = $candidatureRepository->findOneBy(["id" => $id]);
        $manager->remove($candidature);
        $manager->flush();

        $this->addFlash(
            'success',
            'La candidature a bien été supprimée !'
        );

        return $this->redirectToRoute('app_candidature');
    }
}
