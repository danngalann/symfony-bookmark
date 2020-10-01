<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class IdiomaSubscriber implements EventSubscriberInterface
{
    public function comienzoRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale(
                $request
                    ->getSession()
                    ->get('_locale', $request->getDefaultLocale())
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['comienzoRequest', 20]],
        ];
    }
}
