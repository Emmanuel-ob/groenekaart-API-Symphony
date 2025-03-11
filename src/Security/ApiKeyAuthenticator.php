<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Exception\BhException;

class ApiKeyAuthenticator
{
  public function __construct(
    private HttpClientInterface $client,
    #[Autowire(env: 'ACCESS_API_TOKEN')] readonly private string $accessApiKey,
    #[Autowire(env: 'ACCESS_API_URL')] readonly private string $accessApiUrl,
  ) {
  }

  /**
  *
  * @return array<string, string> $data
  */
  public function authenticateApiKey(Request $request): array
  {
    $accessApiToken = $request->get('access-token') ?? $request->get('api-token');

    if (null === $accessApiToken) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
      throw new CustomUserMessageAuthenticationException('No API token provided');
    }
    $app = "api-esb-groenekaart";
    $scope = "none";
    $url = sprintf("%svalidate/%s/%s/%s", $this->accessApiUrl, $app, $accessApiToken, $scope); // @phpstan-ignore-line

    $response = $this->client->request(
      'GET',
      $url,
      [
            'query' => ["access_token" => $this->accessApiKey]
        ],
    );

    if ("true" != $response->getContent()) {
       // Code 401 "Unauthorized"
      throw new BhException(
        "Invalid API token provided",
        "Invalid API token provided: provide a valid one.",
        "/",
        JsonResponse::HTTP_UNAUTHORIZED,
        JsonResponse::HTTP_UNAUTHORIZED
      );
    }

    $res = ["content" => $response->getContent()];
    return $res;
  }
}
