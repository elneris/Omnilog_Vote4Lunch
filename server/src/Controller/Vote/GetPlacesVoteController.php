<?php


namespace App\Controller\Vote;


use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GetPlacesVoteController
{
    private $voteRepository;

    public function __construct(VoteRepository $repository)
    {
        $this->voteRepository = $repository;
    }

    public function __invoke(Request $request)
    {
        $data = $request->query->get('vote_url');

        $vote = $this->voteRepository->findOneBy(['url' => $data]);

        if ($vote) {
            $returnResponse = $vote->getPlaces()->toArray();

            return new JsonResponse($returnResponse);
        }

        throw new BadRequestHttpException('Errors no vote found',null, 400);
    }
}
