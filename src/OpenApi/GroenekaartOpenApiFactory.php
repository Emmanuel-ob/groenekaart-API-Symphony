<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model\Response;
use ApiPlatform\OpenApi\Model\RequestBody;
use ArrayObject;

class GroenekaartOpenApiFactory implements OpenApiFactoryInterface
{
  private OpenApiFactoryInterface $decorated;

  public function __construct(OpenApiFactoryInterface $decorated)
  {
    $this->decorated = $decorated;
  }

  public function __invoke(array $context = []): OpenApi // @phpstan-ignore-line
  {
    $openApi = $this->decorated->__invoke($context);
    $paths = $openApi->getPaths();

    // Add documentation for the /opvragen
    if ($paths->getPath('/opvragen')) {
      $operation = new Operation(
        operationId: 'opvragenEndpoint',
        tags: ['Groenekaart'],
        summary: 'Fetch kenteken information',
        responses: [
                '200' => new Response(
                  description: 'Successful response',
                  content: new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'kenteken' => ['type' => 'string'],
                                    'pdf' => ['type' => 'string'],
                                    'jpg' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ])
                ),
                '400' => new Response(
                  description: 'Missing required query parameter: kenteken'
                ),
            ],
        parameters: [
                [
                    'name' => 'kenteken',
                    'in' => 'query',
                    'required' => true,
                    'schema' => ['type' => 'string'],
                    'description' => 'Required kenteken parameter',
                ]
            ]
      );

      $paths->addPath('/opvragen', new PathItem(get: $operation));
    }


    // Add documentation for the /cleanup
    if ($paths->getPath('/cleanup')) {
      $operation = new Operation(
        operationId: 'cleanupEndpoint',
        tags: ['Groenekaart'],
        summary: 'Data Cleanup',
        responses: [
                    '200' => new Response(
                      description: 'Successful response',
                      content: new ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'success' => ['type' => 'bool'],
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ])
                    ),
                    '400' => new Response(
                      description: 'Invalid query parameter: validity_period'
                    ),
                ],
        parameters: [
                    [
                        'name' => 'validity_period',
                        'in' => 'query',
                        'required' => false,
                        'schema' => ['type' => 'integer'],
                        'description' => 'validity_period parameter',
                    ]
                ]
      );

      $paths->addPath('/cleanup', new PathItem(get: $operation));
    }

    // Add documentation for the /import_data
    if ($paths->getPath('/import_data')) {
      $operation = new Operation(
        operationId: 'importDataEndpoint',
        tags: ['Groenekaart'],
        summary: 'Import Data',
        responses: [
                    '200' => new Response(
                      description: 'Successful response',
                      content: new ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ])
                    ),
                ]
      );

      $paths->addPath('/import_data', new PathItem(get: $operation));
    }


    // Add documentation for the /get_vehicle_info
    if ($paths->getPath('/get_vehicle_info')) {
      $operation = new Operation(
        operationId: 'getVehicleInfoEndpoint',
        tags: ['Groenekaart'],
        summary: 'Get Vehicle Information',
        responses: [
                  '200' => new Response(
                    description: 'Successful response',
                    content: new ArrayObject([
                          'application/json' => [
                              'schema' => [
                                  'type' => 'object',
                                  'properties' => [
                                       'id' => ['type' => 'string'],
                                       'vehicle' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'license_plate' => ['type' => 'string', 'example' => '5342ha'],
                                                'vehicle' => ['type' => 'string', 'example' => '406'],
                                                'organization' => ['type' => 'string', 'example' => 'peogeot'],
                                            ],
                                            'required' => []
                                       ],
                                       'contact' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'category' => ['type' => 'string', 'example' => 'test'],
                                                'salutation' => ['type' => 'string', 'example' => 'Mr'],
                                                'email_address' => ['type' => 'string', 'example' => 'test@broekhuis.nl'],
                                            ],
                                            'required' => []
                                       ],
                                       'document' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'pdf' => ['type' => 'string', 'example' => 'https://some_link.pdf'],
                                                'jpg' => ['type' => 'string', 'example' => 'https://some_link.jpg'],
                                            ],
                                            'required' => []
                                        ],
                                       'errors' => ['type' => 'string'],
                                    ],
                                    'required' => []
                                ],
                            ],
                      ])
                  ),
                  '400' => new Response(
                    description: 'Missing required query parameter: license_plate'
                  ),
              ],
        parameters: [
                [
                    'name' => 'license_plate',
                    'in' => 'query',
                    'required' => true,
                    'schema' => ['type' => 'string'],
                    'description' => 'Required license_plate parameter',
                ],
                [
                    'name' => 'email_address',
                    'in' => 'query',
                    'required' => true,
                    'schema' => ['type' => 'string'],
                    'description' => 'Required email_address parameter',
                ]
            ]
      );

      $paths->addPath('/get_vehicle_info', new PathItem(get: $operation));
    }


    return $openApi;
  }
}
