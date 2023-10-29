<?php

namespace Luzrain\WorkermanBundle\Scheduler;

use Luzrain\WorkermanBundle\Events\TaskErrorEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ErrorListener implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TaskErrorEvent::class => ['onException', -128],
        ];
    }

    public function onException(TaskErrorEvent $event): void
    {
        $this->logger->critical('Error thrown while executing job "{job}". Message: "{message}"', [
            'exception' => $event->getError(),
            'job' => $event->getTaskName(),
            'message' => $event->getError()->getMessage(),
        ]);
    }
}
