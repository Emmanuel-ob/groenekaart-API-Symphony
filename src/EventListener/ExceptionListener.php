<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::EXCEPTION => ['onKernelException', 0],
      ];
  }

  public function onKernelException(ExceptionEvent $event): void
  {
    $exception = $event->getThrowable();

    if ($exception instanceof ValidationFailedException) {
      $violations = $exception->getViolations();

      $errors = $this->formatValidationErrors($violations);

      $response = new JsonResponse([
            'status' => 400,
            'code' => $exception->getCode(),
            'title' => 'Validation errors occurred',
            'description' => $errors,
            'source' => "/",
        ], JsonResponse::HTTP_BAD_REQUEST);

      $event->setResponse($response);
      return;
    }
  }

        /**
         * @param ConstraintViolationListInterface $violations
         * @return array<array<string, string>> An array of associative arrays with 'field' and 'message' keys, where message is always a string
         */
  private function formatValidationErrors(ConstraintViolationListInterface $violations): array
  {
    $errors = [];
    foreach ($violations as $violation) {
      $errors[] = [
            'field' => $violation->getPropertyPath(),
            'message' => (string) $violation->getMessage(),
        ];
    }
    return $errors;
  }
}
