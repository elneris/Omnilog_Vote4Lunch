<?php


namespace App\Controller\Vote;


use App\Repository\VoiceRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DelVoteController extends AbstractController
{
    private $voteRepo;

    private $voiceRepo;

    private $manager;

    public function __construct(VoteRepository $voteRepository, EntityManagerInterface $manager, VoiceRepository $voiceRepository)
    {
        $this->voteRepo = $voteRepository;
        $this->voiceRepo = $voiceRepository;
        $this->manager = $manager;
    }

    public function __invoke(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $vote = $this->voteRepo->findOneBy(['url' => $content['vote_url']]);
        $voices = $this->voiceRepo->findBy(['vote' => $vote]);

        foreach ($voices as $voice) {
            $this->manager->remove($voice);
            $this->manager->flush();
        }

        $url = $this->getParameter('param.url') . 'api/votes/' . $vote->getId();

        $this->manager->remove($vote);
        $this->manager->flush();

        return new JsonResponse(['delete' => true]);
    }
}
