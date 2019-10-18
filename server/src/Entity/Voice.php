<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Voice\AddVoiceController;
use App\Controller\Voice\DelVoiceController;
use App\Controller\Voice\CountAllVoiceByPlaceController;
use App\Controller\Voice\GetAllVoiceByVoteController;
use App\Controller\Voice\GetAllVoiceForUserController;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoiceRepository")
 * @ApiResource(
 *     collectionOperations={
 *         "count_all"={
 *             "method"="GET",
 *             "path"="/voices/count/all",
 *             "controller"=CountAllVoiceByPlaceController::class,
 *             "swagger_context"={
 *                 "summary": "Compter et Récupérer une collection de voix",
 *                 "description": "
 *    Permet de récupérer un tableau avec le nombre total de voix et la liste des ces voix pour un restaurant et pour un vote",
 *                 "parameters"={
 *                      {"name"="vote_id", "in"="query", "type"="integer", "required"=true},
 *                      {"name"="place_id", "in"="query", "type"="integer", "required"=true},
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un integer et un tableau de voix",
 *                          "schema" =  {"properties" = Voice::API_COUNT_VOICE},
 *                      },
 *                  },
 *             },
 *         },
 *         "get_all_voice_all_votes"={
 *             "method"="GET",
 *             "path"="/voices/get/all/for_user",
 *             "controller"=GetAllVoiceForUserController::class,
 *             "swagger_context"={
 *                 "summary": "Récupérer une collection de voix",
 *                 "description": "
 *    Permet de récupérer la liste des voix d'un utilisateur pour un vote",
 *                 "parameters"={
 *                      {"name"="pseudo", "in"="query", "type"="string", "required"=true},
 *                      {"name"="email", "in"="query", "type"="string", "required"=true},
 *                      {"name"="votes_url", "in"="query", "type"="string", "required"=true},
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un tableau de voix",
 *                          "schema" =  {
 *                              "type" = "array",
 *                              "items" = {
 *                                  "properties" = Voice::API_VOICE,
 *                              },
 *                          },
 *                      },
 *                  },
 *             },
 *         },
 *         "get_all"={
 *             "method"="GET",
 *             "path"="/voices/get/all",
 *             "controller"=GetAllVoiceByVoteController::class,
 *             "swagger_context"={
 *                 "summary": "Récupérer une collection de voix",
 *                 "description": "
 *    Permet de récupérer la liste des voix par vote",
 *                 "parameters"={
 *                      {"name"="vote_url", "in"="query", "type"="string", "required"=true},
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un tableau de voix",
 *                          "schema" =  {
 *                              "type" = "array",
 *                              "items" = {
 *                                  "properties" = Voice::API_VOICE,
 *                              },
 *                          },
 *                      },
 *                  },
 *             },
 *         },
 *         "add_voice"={
 *             "method"="POST",
 *             "path"="/voices/add",
 *             "controller"=AddVoiceController::class,
 *             "swagger_context"={
 *                 "summary": "Créer une voix",
 *                 "description": "",
 *                 "parameters"={
 *                      {
 *                          "name"="voice",
 *                          "in"="body",
 *                          "required"=true,
 *                          "description"="",
 *                          "schema" =  {
 *                              "properties" = {
 *                                  "pseudo": {"type": "string"},
 *                                  "place_id": {"type": "integer"},
 *                                  "pseudo": {"type": "string"},
 *                                  "vote_url": {"type": "string"},
 *                              },
 *                          },
 *                      },
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un boolean",
 *                          "schema" =  {"properties" = {"vote": {"type": "boolean"}}}
 *                      },
 *                  },
 *             },
 *         },
 *         "del_voice"={
 *             "method"="DELETE",
 *             "path"="/voices/del",
 *             "controller"=DelVoiceController::class,
 *             "swagger_context"={
 *                 "summary": "Supprimer une voix",
 *                 "description": "",
 *                 "parameters"={
 *                      {
 *                          "name"="voice",
 *                          "in"="body",
 *                          "required"=true,
 *                          "description"="",
 *                          "schema" =  {
 *                              "properties" = {
 *                                  "pseudo": {"type": "string"},
 *                                  "place_id": {"type": "integer"},
 *                                  "pseudo": {"type": "string"},
 *                                  "vote_url": {"type": "string"},
 *                              },
 *                          },
 *                      },
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un boolean",
 *                          "schema" =  {"properties" = {"deleted": {"type": "boolean"}}}
 *                      },
 *                  },
 *             },
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "swagger_context"={
 *                 "summary": "Récupérer une voix",
 *                 "description": "
 *    Filtre par Id",
 *             },
 *         }
 *     }
 * )
 */
class Voice
{
    public const API_COUNT_VOICE = [
        'count' => ['type' => 'integer'],
        'rows' => [
            'type' => 'array',
            'items' => [
                'properties' => Voice::API_VOICE

            ]

        ]
    ];

    public const API_VOICE = [
        'id' => ['type' => 'integer'],
        'pseudo' => ['type' => 'string'],
        'email' => ['type' => 'string'],
        'updateAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'createdAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ]
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champs est obligatoire")
     * @Assert\Length(
     *     min= 2,
     *     max= 50,
     *     minMessage="Minimum 4 caracters",
     *     maxMessage="Maximum 50 caracters"
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champs est obligatoire")
     * @Assert\Email(message="Cet email n'est pas valide")
     * @Assert\Length(
     *     min= 3,
     *     max= 50,
     *     minMessage="Minimum 4 caracters",
     *     maxMessage="Maximum 50 caracters"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vote", inversedBy="voices")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $vote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="voices")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="ce champs est obligatoire")
     */
    private $place;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return ('Vote n°' . $this->getId() . ' : ' . $this->getEmail() . '(' . $this->getPseudo() . ')');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getVote(): ?Vote
    {
        return $this->vote;
    }

    public function setVote(?Vote $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }
}
