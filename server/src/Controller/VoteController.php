<?php


namespace App\Controller;


use App\Entity\Vote;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/vote")
 * Class VoteController
 * @package App\Controller
 */
class VoteController extends AbstractController
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    /**
     * VoteController constructor.
     * @param EntityManagerInterface $manager
     * @param VoteRepository $voteRepository
     * @param UserRepository $userRepository
     * @param PlaceRepository $placeRepository
     */
    public function __construct(EntityManagerInterface $manager, VoteRepository $voteRepository, UserRepository $userRepository, PlaceRepository $placeRepository)
    {
        $this->manager = $manager;
        $this->voteRepository = $voteRepository;
        $this->userRepository = $userRepository;
        $this->placeRepository = $placeRepository;
    }


    /*public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data['date'] && $data['end_date'] && $data['pseudo'] && $data['email'] && $data['title']) {
            $user = $this->userRepository->findOneBy(['pseudo' => $data['pseudo']]);

            if (!$user) {
                throw new BadRequestHttpException('User not found','', 400);
            }

            $vote = new Vote();
            $vote->setUser($user);
            $vote->setTitle($data['title']);
            $vote->setDate(new \DateTime($data['date']));
            $vote->setEndDate(new \DateTime($data['end_date']));

            $this->manager->persist($vote);
            $this->manager->flush();

            $jsonVote = json_encode($vote);

            return new Response($jsonVote);
        }

        throw new BadRequestHttpException('Error when add a new vote',null, 400);
    }*/

    /**
     * Get a vote from his url and delete it
     *
     * @Route("/del", name="vote_del", methods={"post"})
     * @param Request $request
     * @return JsonResponse
     */
    public function del(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $vote = $this->voteRepository->findOneBy(['url' => $data['vote_url']]);
        $this->manager->remove($vote);
        $this->manager->flush();

        return new JsonResponse(['delete' => true]);
    }


  /*  public function getVote(Request $request): Response
    {
        $data = $request->query->all();

        $vote = $this->voteRepository->findOneBy(['url' => $data['vote_url']]);

        $jsonVote = json_encode($vote);

        return new Response($jsonVote);
    }*/


    /*public function getMine(Request $request): Response
    {
        $data = $request->query->all();

        $user = $this->userRepository->findOneBy(['pseudo' => $data['pseudo']]);

        $votes = $this->voteRepository->findBy(['user' => $user], ['date' => 'ASC']);

        return new Response(json_encode($votes));
    }*/


    /*public function addPlace(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $vote = $this->voteRepository->findOneBy(['id' => $data['vote_id']]);

        if ($vote) {
            $vote->setActive(1);

            $this->manager->flush();

            $place = $this->placeRepository->findOneBy(['id' => $data['place_id']]);

            if ($place) {
                $vote->addPlace($place);

                $this->manager->flush();
                $returnResponse = json_encode(['added' => true, 'place' => $place]);

                return new Response($returnResponse);
            }

            throw new BadRequestHttpException('Errors no place found', null, 400);

        }

        throw new BadRequestHttpException('Errors no vote found',null, 400);
    }*/


    /*public function makeThings(Vote $vote): void
    {
        if ($vote->getPlaces()->toArray() === []) {
            $vote->setActive(0);

            $this->manager->flush();
        }
    }*/


    /*public function delPlace(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $vote = $this->voteRepository->findOneBy(['id' => $data['vote_id']]);

        if ($vote) {
            $place = $this->placeRepository->findOneBy(['id' => $data['place_id']]);

            if ($place) {
                $vote->removePlace($place);
                $this->manager->flush();

                $this->makeThings($vote);

                $returnResponse = json_encode(['deleted' => true, 'place' => $place]);

                return new Response($returnResponse);
            }

            throw new BadRequestHttpException('Errors no place found',null, 400);
        }

        throw new BadRequestHttpException('Errors no vote found',null, 400);
    }*/


    /*public function getPlacesList(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $vote = $this->voteRepository->findOneBy(['url' => $data['vote_url']]);

        if ($vote) {
            $returnResponse = $vote->getPlaces()->toArray();

            return new JsonResponse($returnResponse);
        }

        throw new BadRequestHttpException('Errors no vote found',null, 400);
    }*/
}
