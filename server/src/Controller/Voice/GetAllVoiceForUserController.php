<?php


namespace App\Controller\Voice;


use App\Repository\VoiceRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GetAllVoiceForUserController
{
    private $voteRepository;

    private $voiceRepository;

    private $voices;

    public function __construct(
        VoteRepository $voteRepository,
        VoiceRepository $voiceRepository
    ) {
        $this->voteRepository = $voteRepository;
        $this->voiceRepository = $voiceRepository;
    }

    public function __invoke(Request $request)
    {
        $data = $request->query->all();

        $vote = $this->voteRepository->findOneBy(['url' => $data['votes_url']]);

        $voices = $this->voiceRepository->findBy(['vote' => $vote, 'pseudo' => $data['pseudo'], 'email' => $data['email']]);

        $this->voices = $voices;

        return new Response(json_encode($this->jsonSerialize()));
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $result = [];

        foreach ($this->voices as $voice) {
            $result[] = [
                'id' => $voice->getId(),
                'pseudo' => $voice->getPseudo(),
                'email' => $voice->getEmail(),
                'updatedAt' => $voice->getUpdatedAt(),
                'createdAt' => $voice->getCreatedAt(),
                'voteId' => $voice->getVote()->getId(),
                'placeId' => $voice->getPlace()->getId(),
            ];
        }

        return $result;
    }
}
