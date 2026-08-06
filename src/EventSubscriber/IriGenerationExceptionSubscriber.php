<?php
namespace App\EventSubscriber;

use ApiPlatform\Metadata\Exception\InvalidArgumentException as ApiInvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class IriGenerationExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof ApiInvalidArgumentException || str_contains($e->getMessage(), 'Unable to generate an IRI for the item of type')) {
            $request = $event->getRequest();
            $data = [
                'message' => $e->getMessage(),
                'route' => $request->attributes->get('_route'),
                'api_resource_class' => $request->attributes->get('_api_resource_class'),
                'api_operation_name' => $request->attributes->get('_api_operation_name'),
                'api_operation' => $request->attributes->get('_api_operation') ? get_class($request->attributes->get('_api_operation')) : null,
                'route_params' => $request->attributes->all(),
                'request_content' => $request->getContent(),
            ];

            $this->logger->error('IRI generation failure pipeline context', $data);
        }
    }
}
