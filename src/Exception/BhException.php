<?php

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BhException extends \Exception
{
  public function __construct(
    string $message = "Custom Exception",
    private readonly string $description = "Custom Exception Description",
    private readonly string $pointer = '/',
    private readonly int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
    int $code = 0,
  ) {
    parent::__construct($message, $code);
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function getPointer(): string
  {
    return $this->pointer;
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }
}
