<?php

namespace App\Controller;

use App\Form\SearchAvailabilityType;
use App\Repository\AvailabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'search_availability')]
    public function searchAvailability(Request $request, AvailabilityRepository $availabilityRepository): Response
    {

        $searchAvailabilityForm = $this->createForm(SearchAvailabilityType::class); 

        if($searchAvailabilityForm->handleRequest($request) -> isSubmitted() && $searchAvailabilityForm->isValid()) {
            $duration = $searchAvailabilityForm->getData();
            $availability = $availabilityRepository->findByAvailability($duration);
            dd($availability);
        }
        return $this->render('search/availability.html.twig', [
            'search_form' => $searchAvailabilityForm->createView(),
        ]);
    }
}
