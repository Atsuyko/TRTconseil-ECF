<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Candidature;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{
    #[Route('/candidature', name: 'app_candidature')]
    public function index(): Response
    {
        return $this->render('candidature/index.html.twig', [
            'controller_name' => 'CandidatureController',
        ]);
    }

    #[Route('/candidature/new', name: 'candidature.new')]
    public function new(CandidatRepository $candidatRepository, AnnonceRepository $annonceRepository, EntityManagerInterface $manager, Annonce $annonce): Response
    {
        $currentUser = $this->getUser();
        $candidats = $candidatRepository->findAll();
        foreach ($candidats as $candidat) {
            if ($candidat->getUser() === $currentUser) {
                $currentCandidat = $candidat;
            }
        }

        $currentAnnonce = $annonceRepository->findOneBy(['id' => $annonce]);

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
}
