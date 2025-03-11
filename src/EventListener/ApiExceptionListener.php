<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::EXCEPTION => ['onKernelException', -10],
      ];
  }

  public function onKernelException(ExceptionEvent $event): void
  {
    $exception = $event->getThrowable();

    if ($exception instanceof HttpExceptionInterface) {
      $data = [
            'status' => $exception->getStatusCode(),
            'code' => $exception->getCode(),
            'title' => $this->getErrorTitle($exception->getStatusCode()),
            'description' => $exception->getMessage(),
            'source' => $exception->getTrace(),
        ];

      $response = new JsonResponse($data, $exception->getStatusCode());
      $event->setResponse($response);
      return;
    }

            // Default response for any unexpected exception
    $data = [
        'status' => 500,
        'code' => 0,
        'title' => 'Internal Server Error',
        'description' => 'An unexpected error occurred.',
        'source' => $exception->__toString(),
    ];

    $response = new JsonResponse($data, 500);
    $event->setResponse($response);
  }

  private function getErrorTitle(int $statusCode): string
  {
    return match ($statusCode) {
      404 => 'Resource Not Found',
      403 => 'Forbidden',
      400 => 'Bad Request',
      default => 'An error occurred',
    };
  }
}
