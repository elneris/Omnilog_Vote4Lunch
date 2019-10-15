<?php


namespace App\Events;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Vote;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class VoteUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    private $repository;

    private $manager;

    /**
     * VoteUserSubscriber constructor.
     * @param Security $security
     */
    public function __construct(Security $security, EntityManagerInterface $manager, VoteRepository $repository)
    {
        $this->security = $security;
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setOptionForVote', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setOptionForVote(ViewEvent $event): void
    {
        $votes = $this->repository->findBy(['active' => null]);
        foreach ($votes as $vote) {
            $this->manager->remove($vote);
        }

        $vote = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($vote instanceof Vote && $method === 'POST') {
            $user = $this->security->getUser();

            $vote->setUser($user);
        }
    }

}
