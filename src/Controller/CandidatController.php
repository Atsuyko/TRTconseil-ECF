<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatController extends AbstractController
{
    #[Route('/candidat', name: 'app_candidat')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $currentUser = $this->getUser();

        $newCandidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $newCandidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newCandidat->setUser($currentUser);
            $newCandidat->setPrenom($form->get('prenom')->getData());
            $newCandidat->setNom($form->get('nom')->getData());
            $newCandidat->setCv(true);

            $manager->persist($newCandidat);
            $manager->flush();

            return $this->redirectToRoute('app_annonce');
        }
        return $this->render('candidat/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
