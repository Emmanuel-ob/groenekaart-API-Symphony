<?php

namespace App\EventListener;

use App\Exception\BhException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class BhExceptionListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::EXCEPTION => ['onKernelException', 10],
    ];
  }

  public function onKernelException(ExceptionEvent $event): void
  {
    $exception = $event->getThrowable();

        // Check if the exception is of type BH_Exception
    if ($exception instanceof BhException) {
      $statusCode = ($exception->getStatusCode() >= 300) ? $exception->getStatusCode() : JsonResponse::HTTP_BAD_REQUEST;
      $response = new JsonResponse([
            'status' => $statusCode,
            'code' => $exception->getCode(),
            'title' => $exception->getMessage(),
            'description' => $exception->getDescription(),
            'source' => $exception->getPointer(),
            'type' => 'broekhuis custom error'
        ], $statusCode);

      $event->setResponse($response);
    }
  }
}
