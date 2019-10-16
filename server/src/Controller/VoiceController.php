<?php


namespace App\Controller;


use App\Entity\Voice;
use App\Repository\PlaceRepository;
use App\Repository\VoiceRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/voice")
 * Class VoiceController
 * @package App\Controller
 */
class VoiceController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var VoteRepository
     */
    private $voteRepository;

    /**
     * @var VoiceRepository
     */
    private $voiceRepository;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    /**
     * VoiceController constructor.
     * @param EntityManagerInterface $manager
     * @param VoteRepository $voteRepository
     * @param VoiceRepository $voiceRepository
     * @param PlaceRepository $placeRepository
     */
    public function __construct(EntityManagerInterface $manager, VoteRepository $voteRepository, VoiceRepository $voiceRepository, PlaceRepository $placeRepository)
    {
        $this->voteRepository = $voteRepository;
        $this->manager = $manager;
        $this->voiceRepository = $voiceRepository;
        $this->placeRepository = $placeRepository;
    }

    // Old code that i let for my own experience use for classic api without passing by api platform

    /*public function add(Request $request): JsonResponse
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

                $this->manager->persist($voice);
                $this->manager->flush();

                return new JsonResponse(['vote' => true]);
            }

            throw new BadRequestHttpException('Error vote not found',null, 400);
        }

        throw new BadRequestHttpException('Error when add a new voice',null, 400);
    }*/


    /*public function delete(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['vote_url'], $data['place_id'], $data['pseudo'], $data['email'])) {
            $vote = $this->voteRepository->findOneBy(['url' => $data['vote_url']]);

            if ($vote) {
                $voice = $this->voiceRepository->findBy([
                    'pseudo' => $data['pseudo'],
                    'email' => $data['email'],
                    'place' => $data['place_id'],
                    'vote' => $vote->getId(),
                ]);

                if ($voice) {
                    $this->manager->remove($voice);
                    $this->manager->flush();

                    return new JsonResponse(['deleted' => true]);
                }

                throw new BadRequestHttpException('Error voice not found',null, 400);
            }

            throw new BadRequestHttpException('Error vote not found',null, 400);
        }

        throw new BadRequestHttpException('Error when delete a voice',null, 400);
    }*/


    /*public function countAll(Request $request): Response
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
                $returnResponse = json_encode($arrayReturn);

                return new Response($returnResponse);
            }

            throw new BadRequestHttpException('Error place not found',null, 400);

        }

        throw new BadRequestHttpException('Error vote not found',null, 400);
    }*/

    public function getAllForuser(Request $request): Response
    {
        $data = $request->query->all();

        $urls = json_decode($data['votes_url'], true);

        $returnResponse = [];

        foreach ($urls as $url) {
            $vote = $this->voteRepository->findOneBy(['url' => $url]);

            $voices = $this->voiceRepository->findBy(['vote' => $vote, 'pseudo' => $data['pseudo'], 'email' => $data['email']]);

            foreach ($voices as $voice) {
                $returnResponse[] = $voices;
            }
        }
        $jsonReturn = json_encode($returnResponse);

        return new Response($jsonReturn);
    }


    /*public function getAllForvote(Request $request): Response
    {
        $data = $request->query->all();

        $vote = $this->voteRepository->findOneBy(['url' => $data['vote_url']]);

        if ($vote) {
            $voices = $this->voiceRepository->findBy(['vote' => $vote]);

            $jsonVoices = json_encode($voices);

            return new Response($jsonVoices);
        }

        throw new BadRequestHttpException('Error vote not found',null, 400);
    }*/
}
