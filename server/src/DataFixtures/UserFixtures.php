<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $hash = $this->encoder->encodePassword($user, 'test');
        $user->setPseudo('ElnerisFix');
        $user->setEmail('elnerfix@fixture.com');
        $user->setPassword($hash);

        $manager->persist($user);

        $manager->flush();
    }
}
