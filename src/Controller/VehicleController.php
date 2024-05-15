<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController


{

    /**
     * @Route("/vehicules", name="vehicle_index")
     * 
     * @param ManagerRegistry $doctrine The Doctrine registry.
     * @return Response A HTTP response object.
     * 
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $vehicles = $doctrine->getRepository(Vehicle::class)->findAll();

        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * @Route("/vehicules/ajouter", name="vehicle_create")
     * 
     * @param Request $request The HTTP request object.
     * @param ManagerRegistry $doctrine The Doctrine registry.
     * @return Response A HTTP response object.
     * 
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $vehicle = new Vehicle();

        $form = $this->createForm(VehicleType::class, $vehicle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            
            $existingVehicle = $entityManager->getRepository(Vehicle::class)->findOneBy([
            'brand' => $vehicle->getBrand(),
            'model' => $vehicle->getModel(),
            ]);
            if ($existingVehicle !== null) {
                $this->addFlash('danger', 'Ce véhicule existe déjà.');
            } else {        
                $entityManager->persist($vehicle);
                $entityManager->flush();
                $this->addFlash('success', 'Le véhicule a bien été ajouté.');

                return $this->redirectToRoute('vehicle_index');
            }
        }

        return $this->render('vehicle/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
