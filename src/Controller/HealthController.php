<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthController
{
  #[Route('/health', name: 'app_health_index', methods: ['GET'])]
  public function index(): Response
  {
    return new Response('Hello World!');
  }
}
