<?php


namespace App\Controller\Voice;


use App\Repository\PlaceRepository;
use App\Repository\VoiceRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CountAllVoiceByPlaceController implements \JsonSerializable
{
    private $voteRepository;

    private $placeRepository;

    private $voiceRepository;

    private $countArray;

    public function __construct(
        VoteRepository $voteRepository,
        PlaceRepository $placeRepository,
        VoiceRepository $voiceRepository
    ) {
        $this->voteRepository = $voteRepository;
        $this->placeRepository = $placeRepository;
        $this->voiceRepository = $voiceRepository;
    }

    public function __invoke(Request $request)
    {
        $data = $request->query->all();

        $vote = $this->voteRepository->findBy(['id' => $data['vote_id']]);

        if ($vote) {
            $place = $this->placeRepository->findBy(['id' => $data['place_id']]);

            if ($place) {
                $voices =$this->voiceRepository->findBy(['vote' => $vote, 'place' => $place]);

                $arrayReturn = [
                    'count' => count($voices),
                    'rows' => $voices
                ];

                $this->countArray = $arrayReturn;


                return new Response(json_encode($this->jsonSerialize()));
            }

            throw new BadRequestHttpException('Error place not found',null, 400);

        }

        throw new BadRequestHttpException('Error vote not found',null, 400);
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
        $result= [
            'count' => $this->countArray['count'],
            'rows' => []
            ];

        foreach ($this->countArray['rows'] as $voice) {
            $result['rows'][] = [
                'id' => $voice->getId(),
                'pseudo' => $voice->getPseudo(),
                'email' => $voice->getEmail(),
                'updatedAt' => $voice->getUpdatedAt(),
                'createdAt' => $voice->getCreatedAt(),
            ];
        }
        return $result;
    }
}
