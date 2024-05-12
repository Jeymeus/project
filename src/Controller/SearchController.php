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
    /**
     * #[Route('/recherche', name: 'search_availability')]
    */
    public function searchAvailability(Request $request, AvailabilityRepository $availabilityRepository): Response
    {
        $availabilities = [];
        $searchAvailabilityForm = $this->createForm(SearchAvailabilityType::class); 

        if($searchAvailabilityForm->handleRequest($request) -> isSubmitted() && $searchAvailabilityForm->isValid()) {
            
            $duration = $searchAvailabilityForm->getData();
            $start_date = $duration['start_date'];
            $end_date = $duration['end_date'];
            $maxPrice = $duration['maxPrice'];
            $interval = $start_date->diff($end_date);
            $nbDays = ($interval->days) + 1;
            $price_per_day = $maxPrice / $nbDays;
            // dd($price_per_day, $nbDays, $maxPrice);

            // dd($interval->days);
            $availabilities = $availabilityRepository->findByAvailability($duration, $price_per_day);
            // dd($availabilities);
        }
        return $this->render('search/availability.html.twig', [
            'search_form' => $searchAvailabilityForm->createView(),
            'availabilities' => $availabilities,
        ]);
    }
}
