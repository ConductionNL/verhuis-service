<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Service\VerhuisService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Symfony\Component\Serializer\SerializerInterface;


class WebHookSubscriber implements EventSubscriberInterface
{
    private $params;
    private $trouwService;
    private $serializer;
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, VerhuisService $verhuisService, CommongroundService $commonGroundService, SerializerInterface $serializer)
    {
        $this->params = $params;
        $this->verhuisService = $verhuisService;
        $this->commonGroundService = $commonGroundService;
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['webHook', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function webHook(GetResponseForControllerResultEvent $event)
    {
        $webHook = $event->getControllerResult();

        // Mist validatei logica
        // Gaat het hioer bijvoorbeeld wel om de jusite entity


        // Task ophalen
        if($task = $webHook->getTask() && $task = $this->commonGroundService->getResource($task) && array_key_exists('code',$task) ){
            // vier feest je
        }
        else{
            return;
        }

        // Resource ophalen
        if($resource = $webHook->getResouce() && $resource = $this->commonGroundService->getResource($resource) ){
            // vier feest je
        }
        else{
            return;
        }

        $this->verhuisService->webHook($task, $resource);

    }
}
