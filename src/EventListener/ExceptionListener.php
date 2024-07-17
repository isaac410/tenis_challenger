<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener {

    public function onKernelException(ExceptionEvent $event): void {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->setData([
                'title' => Response::$statusTexts[$exception->getStatusCode()],
                'detail' => $exception->getMessage(),
            ]);
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $response->setData([
                'title' => 'Internal Server Error',
                'detail' => $exception->getMessage()
            ]);
        }
        $event->setResponse($response);
    }
}
