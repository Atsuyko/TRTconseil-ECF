<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Candidature;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use Symfony\Component\Mime\Part\File;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidatureRepository;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConsultantController extends AbstractController
{
    /**
     * Display user, annonce, candidature isValidate === false
     *
     * @param UserRepository $userRepository
     * @param AnnonceRepository $annonceRepository
     * @param CandidatureRepository $candidatureRepository
     * @return Response
     */
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

    /**
     * Validate a candidat
     * 
     * @param User $user
     * @param EntityManagerInterface $manager
     */
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

    /**
     * Reject a candidat
     * 
     * @param User $user
     * @param EntityManagerInterface $manager
     */
    #[Route('/consultant/reject-candidat/{id}', name: 'consultant.reject.candidat', methods: ['GET', 'POST'])]
    public function rejectCandidat(User $user, EntityManagerInterface $manager): Response
    {
        $manager->remove($user);
        $manager->flush();

        return $this->render('consultant/reject_candidat.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Validate a recruteur
     * 
     * @param User $user
     * @param EntityManagerInterface $manager
     */
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

    /**
     * reject a recruteur
     * 
     * @param User $user
     * @param EntityManagerInterface $manager
     */
    #[Route('/consultant/reject-recruteur/{id}', name: 'consultant.reject.recruteur', methods: ['GET', 'POST'])]
    public function rejectRecruteur(User $user, EntityManagerInterface $manager): Response
    {
        $manager->remove($user);
        $manager->flush();

        return $this->render('consultant/reject_recruteur.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Validate an annonce
     * 
     * @param Annonce $annonce
     * @param EntityManagerInterface $manager
     */
    #[Route('/consultant/validate-annonce/{id}', name: 'consultant.validate.annonce', methods: ['GET', 'POST'])]
    public function validateAnnonce(Annonce $annonce, EntityManagerInterface $manager): Response
    {
        $annonce->setIsValidate(true);
        $manager->persist($annonce);
        $manager->flush();

        return $this->render('consultant/validate_annonce.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * Reject an annonce
     * 
     * @param Annonce $annonce
     * @param EntityManagerInterface $manager
     */
    #[Route('/consultant/reject-annonce/{id}', name: 'consultant.reject.annonce', methods: ['GET', 'POST'])]
    public function rejectAnnonce(Annonce $annonce, EntityManagerInterface $manager): Response
    {
        $manager->remove($annonce);
        $manager->flush();

        return $this->render('consultant/reject_annonce.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * Validate a candidature
     * 
     * @param Candidature $candidature
     * @param EntityManagerInterface $manager
     */
    #[Route('/consultant/validate-candidature/{id}', name: 'consultant.validate.candidature', methods: ['GET', 'POST'])]
    public function validateCandidature(Candidature $candidature, EntityManagerInterface $manager, MailerInterface $mailer): Response
    {
        $candidature->setIsValidate(true);
        $manager->persist($candidature);
        $manager->flush();

        // $email = (new TemplatedEmail())
        //     ->from('no-reply@trtconseil.fr')
        //     ->to($candidature->getAnnonce()->getRecruteur()->getUser()->getEmail())
        //     ->subject('Nouvelle candidature !')
        //     ->addPart(new DataPart(new File('uploads/cv/' . $candidature->getCandidat()->getCv())))
        //     // path of the Twig template to render
        //     ->htmlTemplate('emails/validate_candidature.html.twig')

        //     // pass variables (name => value) to the template
        //     ->context([
        //         'candidature' => $candidature
        //     ]);

        // $mailer->send($email);

        return $this->render('consultant/validate_candidature.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    /**
     * Reject an candidature
     * 
     * @param Candidature $candidature
     * @param EntityManagerInterface $manager
     */
    #[Route('/consultant/reject-candidature/{id}', name: 'consultant.reject.candidature', methods: ['GET', 'POST'])]
    public function rejectCandidature(Candidature $candidature, EntityManagerInterface $manager): Response
    {
        $manager->remove($candidature);
        $manager->flush();

        return $this->render('consultant/reject_candidature.html.twig', [
            'candidature' => $candidature,
        ]);
    }
}
