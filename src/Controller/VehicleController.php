<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Security\ApiKeyAuthenticator;
use App\Exception\BhException;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;

#[ApiResource(
  operations: [
        new Get(
          uriTemplate: '/opvragen',
          name: 'Fetch kenteken information',
          description: 'Fetch kenteken information',
        ),
        new Get(
          uriTemplate: '/cleanup',
          name: 'Data Cleanup',
          description: 'Data Cleanup',
        ),
        new Get(
          uriTemplate: '/import_data',
          name: 'Import Data',
          description: 'Import Data',
        ),
        new Get(
          uriTemplate: '/get_vehicle_info',
          name: 'Get Vehicle Information',
          description: 'Get Vehicle Information',
        ),
    ]
)]

class VehicleController
{
  #[Route(
    path: '/opvragen',
    name: 'Fetch kenteken information',
    methods: ['GET']
  )]
  public function getKentekenInfo(
    Request $request,
    #[MapQueryParameter] string $kenteken,
    ApiKeyAuthenticator $apiAuth
  ): JsonResponse {
    try {
      $apiAuth->authenticateApiKey($request);
      $response = new JsonResponse([
            'message' => "Successful",
        ], JsonResponse::HTTP_OK);

      return $response;
    } catch (\Exception $e) {
      throw new BhException($e->getMessage(), $e->getMessage(), "/opvragen", $e->getCode(), $e->getCode());
    }
  }

  #[Route(
    path: '/cleanup',
    name: 'Data Cleanup',
    methods: ['GET']
  )]
  public function doCleanup(
    Request $request,
    #[MapQueryParameter('validity_period')] string $validityPeriod,
    ApiKeyAuthenticator $apiAuth
  ): JsonResponse {
    try {
      $apiAuth->authenticateApiKey($request);
      $response = new JsonResponse([
            'message' => "Successful",
        ], JsonResponse::HTTP_OK);

      return $response;
    } catch (\Exception $e) {
      throw new BhException($e->getMessage(), $e->getMessage(), "/cleanup", $e->getCode(), $e->getCode());
    }
  }


  #[Route(
    path: '/import_data',
    name: 'Import Data',
    methods: ['GET']
  )]
  public function importData(
    Request $request,
    ApiKeyAuthenticator $apiAuth
  ): JsonResponse {
    try {
      $apiAuth->authenticateApiKey($request);
      $response = new JsonResponse([
            'message' => "Successful",
        ], JsonResponse::HTTP_OK);

      return $response;
    } catch (\Exception $e) {
      throw new BhException($e->getMessage(), $e->getMessage(), "/import_data", $e->getCode(), $e->getCode());
    }
  }

  #[Route(
    path: '/get_vehicle_info',
    name: 'Get Vehicle Information',
    methods: ['GET']
  )]
  public function getVehicleInfo(
    Request $request,
    #[MapQueryParameter('license_plate')] string $licensePlate,
    #[MapQueryParameter('email_address')] string $emailAddress,
    ApiKeyAuthenticator $apiAuth
  ): JsonResponse {
    try {
      $apiAuth->authenticateApiKey($request);
      $response = new JsonResponse([
            'message' => "Successful",
        ], JsonResponse::HTTP_OK);

      return $response;
    } catch (\Exception $e) {
      throw new BhException($e->getMessage(), $e->getMessage(), "/get_vehicle_info", $e->getCode(), $e->getCode());
    }
  }
}
