<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Interface\CountryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CountryController extends AbstractController
{
    public function __construct(
        private CountryServiceInterface $countryService
    ) {
    }

    public function getList(): JsonResponse
    {
        return $this->json(
            $this->countryService->findAll()
        );
    }
}
