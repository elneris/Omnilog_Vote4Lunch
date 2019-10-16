<?php


namespace App\Controller\Vote;


use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DelVoteController extends AbstractController
{
    private $voteRepo;

    private $manager;

    public function __construct(VoteRepository $voteRepository, EntityManagerInterface $manager)
    {
        $this->voteRepo = $voteRepository;
        $this->manager = $manager;
    }

    public function __invoke(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $vote = $this->voteRepo->findOneBy(['url' => $content['vote_url']]);

        $url = $this->getParameter('param.url') . 'api/votes/' . $vote->getId();

        $this->manager->remove($vote);
        $this->manager->flush();

        return new JsonResponse(['delete' => true]);
    }
}
