<?php


namespace App\Events;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
    /**
     * @param JWTCreatedEvent $event
     */
    public function updateJwtData(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        $data = $event->getData();
        $data['pseudo'] = $user->getPseudo();

        $event->setData($data);
    }
}
