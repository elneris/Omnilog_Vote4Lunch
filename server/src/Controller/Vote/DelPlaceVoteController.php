<?php


namespace App\Controller\Vote;


use App\Entity\Vote;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DelPlaceVoteController
{
    private $manager;

    private $placeRepository;

    public function __construct(EntityManagerInterface $manager, PlaceRepository $repository)
    {
        $this->manager = $manager;
        $this->placeRepository = $repository;
    }

    public function __invoke(Vote $data, Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $place = $this->placeRepository->findOneBy(['id' => $content['place_id']]);

        if ($place) {
            $data->removePlace($place);
            $this->manager->flush();

            $this->makeThings($data);

            $returnResponse = json_encode(['deleted' => true, 'place' => $place]);

            return new Response($returnResponse);
        }

        throw new BadRequestHttpException('Errors no place found', null, 400);

    }

    public function makeThings(Vote $vote)
    {
        if ($vote->getPlaces()->toArray() === []) {
            $vote->setActive(null);

            $this->manager->flush();
        }
    }
}
