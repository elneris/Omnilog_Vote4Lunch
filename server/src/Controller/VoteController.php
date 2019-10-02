<?php


namespace App\Controller;


use App\Entity\Vote;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * Create a vote and return vote data
     *
     * @Route("/add", name="vote_add", methods={"post"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function add(Request $request): Response
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

        throw new BadRequestHttpException('Error when add a new vote','', 400);
    }

    /**
     * Get a vote from his url and delete it
     *
     * @Route("/del", name="vote_del", methods={"post"})
     * @param Request $request
     * @return JsonResponse
     */
    public function del(Request $request): JsonResponse
    {
        $vote = $this->voteRepository->findOneBy(['url' => $request->getContent()['vote_url']]);
        $this->manager->remove($vote);
        $this->manager->flush();

        return new JsonResponse(['delete' => true]);
    }

    /**
     * Get a vote from this url and return it
     *
     * @Route("/getVote", name="vote_getVote", methods={"get"})
     * @param Request $request
     * @return object|void
     */
    public function getVote(Request $request)
    {
        $data = $request->query->all();

        $vote = $this->voteRepository->findOneBy(['url' => $data['vote_url']]);

        $jsonVote = json_encode($vote);

        dd($jsonVote);
    }

    /**
     * Get all votes from a user and return them
     *
     * @Route("/get/mine", name="vote_get_mine", methods={"get"})
     * @param Request $request
     * @return Response
     */
    public function getMine(Request $request): Response
    {
        $data = $request->query->all();

        $user = $this->userRepository->findOneBy(['pseudo' => $data['pseudo']]);

        $votes = $this->voteRepository->findBy(['user' => $user], ['date' => 'ASC']);

        return new Response(json_encode($votes));
    }

    /**
     * Add a place to a vote and return it
     *
     * @Route("/add/place", name="vote_add_place", methods={"post"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addPlace(Request $request): JsonResponse
    {
        $data = $request->getContent();

        $vote = $this->voteRepository->findOneBy(['id' => $data['vote_id']]);

        if ($vote) {
            $vote->setActive(1);

            $place = $this->placeRepository->findOneBy(['id' => $data['place_id']]);

            if ($place) {
                $vote->addPlace($place);

                $jsonplace = json_encode($place);

                return new JsonResponse(['added' => true, 'place' => $jsonplace]);
            }

            throw new BadRequestHttpException('Errors no place found','', 400);

        }

        throw new BadRequestHttpException('Errors no vote found','', 400);
    }
}
