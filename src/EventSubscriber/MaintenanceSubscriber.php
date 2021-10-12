<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    // private $maintenanceIsActive;

    // public function __construct($maintenanceIsActive)
    // {
    //     $this->maintenanceIsActive = $maintenanceIsActive;
    // }

    public function onKernelResponse(ResponseEvent $event)
    {
        $newHtml = $event->getResponse()->getContent();

        // pour récupérer la variable d'environnement on peut 
        // accéder à la super globale $_ENV
            // dd($_ENV['MAINTENANCE_MSG_ACTIVE']);
        // configurer le service (cf services.yaml) et faire une injection de dépendance
        // utiliser l'objet Request qui est dans l'objet $event
        $request = $event->getRequest();

        
        $maintenanceIsActive = $request->server->get('MAINTENANCE_MSG_ACTIVE');
        if ($maintenanceIsActive) 
        {
            $message = $request->server->get('MAINTENANCE_MSG');;
            $newHtml = str_replace('<body>', '<body><div class="alert alert-danger mb-0">' . $message . '</div>', $newHtml);
    
            $event->getResponse()->setContent($newHtml);
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
