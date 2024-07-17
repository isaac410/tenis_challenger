<?php

namespace App\EventListener;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->setData([
                'title' => Response::$statusTexts[$exception->getStatusCode()],
                'detail' => $exception->getMessage(),
            ]);
        } elseif ($exception instanceof UniqueConstraintViolationException) {
            /* $message = 'A record with the same unique value already exists.';
            $details = $exception->getMessage();
            $field = $this->extractFieldFromMessage($details);
            $response->setStatusCode(Response::HTTP_CONFLICT);
            $response->setData([
                'title' => 'Conflict',
                'detail' => $message,
                'field' => $field
            ]); */
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $response->setData([
                'title' => 'Internal Server Error',
                'detail' => $exception->getMessage()
            ]);
        }

        $event->setResponse($response);
    }

    /**
     * Extracts the field implicated in the unique constraint violation from the exception message.
     *
     * @param string $details
     * @return string|null
     */
    private function extractFieldFromMessage(string $details): ?string
    {
        // Ejemplo: "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'value' for key 'unique_key'"
        if (preg_match("/for key '(.+?)'/", $details, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
