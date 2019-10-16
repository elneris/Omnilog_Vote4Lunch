<?php


namespace App\Controller;


use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetMyVoteController implements \JsonSerializable
{
    private $userRepository;

    private $voteRepository;

    private $votes;

    public function __construct(UserRepository $repository, VoteRepository $voteRepository)
    {
        $this->userRepository = $repository;
        $this->voteRepository = $voteRepository;
    }

    public function __invoke(Request $request)
    {
        $data = $request->query->all();

        $user = $this->userRepository->findOneBy(['pseudo' => $data['pseudo']]);

        $votes = $this->voteRepository->findBy(['user' => $user], ['date' => 'ASC']);

        $this->votes = $votes;

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
        foreach ($this->votes as $vote) {
            $result[] = [
                'id' => $vote->getId(),
                'userId' => $vote->getUser()->getId(),
                'title' => $vote->getTitle(),
                'date' => $vote->getDate(),
                'end_date' => $vote->getEndDate(),
                'url' => $vote->getUrl(),
                'updatedAt' => $vote->getUpdatedAt(),
                'createdAt' => $vote->getCreatedAt(),
                'active' => $vote->getActive(),
                'user' => [
                    'pseudo' => $vote->getUser()->getPseudo(),
                    'email' => $vote->getUser()->getEmail(),
                ]
            ];
        }
        return $result;
    }
}
