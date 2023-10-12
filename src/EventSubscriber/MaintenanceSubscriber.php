<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Environment $twig,
        #[Autowire(param: 'env(bool:APP_MAINTENANCE)')]
        private readonly bool $isMaintenance,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->isMaintenance) {
            $response = new Response();

            if ($event->isMainRequest()) {
                $response->setContent($this->twig->render('maintenance.html.twig'));
            }

            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
        ];
    }
}
