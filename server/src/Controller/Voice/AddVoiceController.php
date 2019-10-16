<?php


namespace App\Controller\Voice;


use App\Entity\Voice;
use App\Repository\PlaceRepository;
use App\Repository\VoiceRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddVoiceController
{
    private $manager;

    private $voteRepository;

    private $placeRepository;

    private $voiceRepository;

    public function __construct(
        EntityManagerInterface $manager,
        VoteRepository $voteRepository,
        PlaceRepository $placeRepository,
        VoiceRepository $voiceRepository
    ) {
        $this->manager = $manager;
        $this->voteRepository = $voteRepository;
        $this->placeRepository = $placeRepository;
        $this->voiceRepository = $voiceRepository;
    }

    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['vote_url'], $data['place_id'], $data['pseudo'], $data['email'])) {
            $vote = $this->voteRepository->findOneBy(['url' => $data['vote_url']]);
            $place = $this->placeRepository->findOneBy(['id' => $data['place_id']]);

            if ($vote) {
                $voice = new Voice();
                $voice->setPlace($place);
                $voice->setVote($vote);
                $voice->setPseudo($data['pseudo']);
                $voice->setEmail($data['email']);

                $checkVoice = $this->voiceRepository->findBy([
                    'pseudo' => $data['pseudo'],
                    'email' => $data['email'],
                    'place' => $place,
                    'vote' => $vote,
                ]);

                if (!$checkVoice) {
                    $this->manager->persist($voice);
                    $this->manager->flush();

                    return new JsonResponse(['vote' => true]);
                }

                throw new BadRequestHttpException('Error you already vote for this place',null, 400);

            }

            throw new BadRequestHttpException('Error vote not found',null, 400);
        }

        throw new BadRequestHttpException('Error when add a new voice',null, 400);
    }
}
