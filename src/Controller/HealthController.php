<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;

#[ApiResource(
  operations: [
      new Get(
        uriTemplate: '/health',
        name: 'app_health_index',
        description: 'App health index',
      ),
  ]
)]

class HealthController
{
  #[Route('/health', name: 'app_health_index', methods: ['GET'])]
  public function index(): Response
  {
    return new Response('Hello World!');
  }
}
