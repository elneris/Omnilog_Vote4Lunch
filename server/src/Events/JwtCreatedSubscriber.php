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
        $data['email'] = $user->getEmail();

        $expiration = new \DateTime('+1 day');
        $expiration->setTime(2, 0, 0);

        $data['exp'] = $expiration->getTimestamp();

        $event->setData($data);

    }
}
