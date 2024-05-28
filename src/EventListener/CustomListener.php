<?php
namespace App\EventListener;

use App\CustomEvents\NewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            NewEvent::NAME => 'listenerFunction'
        ];
    }

    public function listenerFunction(NewEvent $NewEvent)
    {
        dump($NewEvent->getNewArticle()->getTitle());
        die();
    }
}