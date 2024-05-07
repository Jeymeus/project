<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Form\AvailabilityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvailabilityController extends AbstractController
{
    #[Route('/availability/create', name: 'availability_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $availability = new Availability();
        $form = $this->createForm(AvailabilityType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($availability);
            $entityManager->flush();

            $this->addFlash('success', 'Disponibilité ajoutée avec succès.');

            return $this->redirectToRoute('vehicle_index');
        }

        return $this->render('availability/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/disponibilites/{id}", name="availability_show")
     */
    public function show(Availability $availability): Response
    {
        return $this->render('availability/show.html.twig', [
            'availability' => $availability,
        ]);
    }

}
