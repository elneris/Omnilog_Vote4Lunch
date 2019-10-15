<?php


namespace App\Controller;


use App\Entity\Vote;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddPlaceVoteController
{
    private $manager;

    private $placeRepository;

    public function __construct(EntityManagerInterface $manager, PlaceRepository $repo)
    {
        $this->manager = $manager;
        $this->placeRepository = $repo;
    }

    public function __invoke(Vote $data, Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if ($content) {
            $data->setActive(1);
            $this->manager->flush();

            $place = $this->placeRepository->findOneBy(['id' => $content['place_id']]);

            if ($place) {
                $data->addPlace($place);

                $this->manager->flush();
                $returnResponse = json_encode(['added' => true, 'place' => $place]);

                return new Response($returnResponse);
            }

            throw new BadRequestHttpException('Errors no place found', null, 400);

        }

        throw new BadRequestHttpException('Errors no vote found',null, 400);
    }

}
