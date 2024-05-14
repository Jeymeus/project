<?php

namespace App\Controller;

use App\Form\SearchAvailabilityType;
use App\Repository\AvailabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{

        public function searchAvailability(Request $request, AvailabilityRepository $availabilityRepository): Response
    {
        $availabilities = [];
        $searchAvailabilityForm = $this->createForm(SearchAvailabilityType::class);

        if ($searchAvailabilityForm->handleRequest($request)->isSubmitted() && $searchAvailabilityForm->isValid()) {
            $duration = $searchAvailabilityForm->getData();
            $price_per_day = $this->calculatePricePerDay($duration);
            $availabilities = $availabilityRepository->findByAvailability($duration, $price_per_day);
        } elseif ($request->isMethod('POST') && $request->request->get('extend_search')) {
            $start_date = \DateTime::createFromFormat('Y-m-d', $request->request->get('start_date'));
            $end_date = \DateTime::createFromFormat('Y-m-d', $request->request->get('end_date'));
            $maxPrice = $request->request->get('maxPrice');

            $durations = $this->generateDateRanges($start_date, $end_date, $maxPrice);

            $availabilities = [];

            foreach ($durations as $duration) {
                $price_per_day = $this->calculatePricePerDay($duration);
                $availabilitiesForDuration = $availabilityRepository->findByAvailability($duration, $price_per_day);

                if (!empty($availabilitiesForDuration)) {
                    $availabilities = $availabilitiesForDuration;
                    break;
                }
            }
        }

        return $this->render('search/availability.html.twig', [
            'search_form' => $searchAvailabilityForm->createView(),
            'availabilities' => $availabilities,
        ]);
    }


    private function calculatePricePerDay(array $duration): float
    {
        $start_date = $duration['start_date'];
        $end_date = $duration['end_date'];
        $maxPrice = $duration['maxPrice'];

        $interval = $start_date->diff($end_date);
        $nbDays = ($interval->days) + 1;

        return $maxPrice / $nbDays;
    }
    
    private function generateDateRanges(\DateTime $start_date, \DateTime $end_date, float $maxPrice): array
    {
        $durations = [];

        $modifications = [
            '-1 day', '-1 day', '-1 day',
            '', '', '',
            '+1 day', '+1 day', '+1 day',
        ];

        foreach ($modifications as $modification) {
            $duration = [
                'start_date' => clone $start_date,
                'end_date' => clone $end_date,
                'maxPrice' => $maxPrice,
            ];

            if (!empty($modification)) {
                $duration['start_date']->modify($modification);
                $duration['end_date']->modify($modification);
            }

            $durations[] = $duration;
        }

        return $durations;
    }
}

