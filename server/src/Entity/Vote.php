<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Vote\AddPlaceVoteController;
use App\Controller\Vote\DelPlaceVoteController;
use App\Controller\Vote\GetPlacesVoteController;
use App\Controller\Vote\GetMyVoteController;
use App\Controller\Vote\DelVoteController;


/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *             "swagger_context"={
 *                 "summary": "Récupérer une collection de votes",
 *                 "description": "
 *    Filtre par url ou utilisateur",
 *                 "parameters"={
 *                      {"name"="url", "in"="query", "type"="string", "required"=false},
 *                      {"name"="user", "in"="query", "type"="string", "required"=false},
 *                  },
 *                 "responses"={
 *                      "201" = {
 *                          "description" = "retourne les vote",
 *                          "schema" =  {
 *                              "type" = "array",
 *                              "items" = {
 *                                  "properties" = Vote::API_VOTE_CREATED,
 *                              },
 *                          },
 *                      },
 *                  },
 *             },
 *         },
 *         "post"={
 *             "swagger_context"={
 *                 "summary": "Créer un vote",
 *                 "description": "
 *    Un jeton d'authentification JWT est requis",
 *                 "parameters"={
 *                      {
 *                          "name"="vote",
 *                          "in"="body",
 *                          "required"=true,
 *                          "description"="L'utilisateur connecté sera automatiquement ajouté au vote grâce au token",
 *                          "schema" =  {"properties" = Vote::API_CREATE_VOTE},
 *                      },
 *                  },
 *                 "responses"={
 *                      "201" = {
 *                          "description" = "retourne le vote",
 *                          "schema" =  {
 *                              "type" = "array",
 *                              "items" = {
 *                                  "properties" = Vote::API_VOTE_CREATED,
 *                              },
 *                          },
 *                      },
 *                  },
 *              },
 *         },
 *         "get_places"={
 *             "method"="GET",
 *             "path"="/votes/places",
 *             "controller"=GetPlacesVoteController::class,
 *             "swagger_context"={
 *                 "summary"="Récupérer une collection de restaurants",
 *                 "description"="
 *    Permet de récupérer la liste des restaurants pour un vote, grâce à son url",
 *                 "parameters"={
 *                      {"name"="vote_url", "in"="query", "type"="string", "required"=true, "description"="Exemple d'url : Ut5Gp"},
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un tableau contenant tout les restaurants d'un vote",
 *                          "schema" =  {
 *                              "type" = "array",
 *                              "items" = {
 *                                  "properties" = Place::API_PLACE,
 *                              },
 *                          },
 *                      },
 *                  },
 *             },
 *         },
 *         "get_myvotes"={
 *             "method"="GET",
 *             "path"="/votes/mines",
 *             "controller"=GetMyVoteController::class,
 *             "swagger_context"={
 *                 "summary": "Récupérer une collection de votes",
 *                 "description": "
 *    Permet de récupérer la liste de tout les votes de l'utilisateur connecté",
 *                 "parameters"={
 *                      {"name"="pseudo", "in"="query", "type"="string", "required"=true},
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne l'ensemble des votes d'un utilisateur",
 *                          "schema" =  {
 *                              "type" = "array",
 *                              "items" = {
 *                                  "properties" = Vote::API_VOTE,
 *                              },
 *                          },
 *                      },
 *                  },
 *             },
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/votes/delete",
 *             "controller"=DelVoteController::class,
 *             "swagger_context"={
 *                 "summary": "Supprimer un vote",
 *                 "description": "
 *    Un jeton d'authentification JWT est requis",
 *                 "parameters"={
 *                     {"name"="vote_url", "in"="query", "type"="string", "required"=true},
 *                  },
 *                 "responses"={
 *                     "204" = {
 *                         "description" = "Renvoie un boolean",
 *                         "schema" =  {"properties" = {"deleted": {"type": "boolean"}}},
 *                     },
 *                 },
 *             },
 *         },
 *
 *     },
 *     itemOperations={
 *         "get"={
 *             "swagger_context"={
 *                 "summary": "Récupérer un vote",
 *                 "description": "
 *    Filtre par Id",
 *             },
 *         },
 *         "delete_place"={
 *             "method"="PUT",
 *             "path"="/votes/{id}/del_place",
 *             "controller"=DelPlaceVoteController::class,
 *             "swagger_context"={
 *                 "summary": "Retirer un restaurant à un vote",
 *                 "description": "
 *   Permet de retirer un restaurant à la liste des restaurants disponible pour un vote
 *   Si c'est le dernier restaurant du vote, le vote passe en état inactif
 *   Un jeton d'authentification JWT est requis",
 *                 "parameters"={
 *                      {
 *                          "name"="vote",
 *                          "in"="body",
 *                          "required"=true,
 *                          "description"="Supprime un restaurant à la collection",
 *                          "schema" =  {
 *                              "properties" = {
 *                                  "place_id": {"type": "integer"},
 *                                  "vote_id": {"type": "integer"}
 *                              },
 *                          },
 *                      },
 *                  },
 *                  "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un boolean et le restaurant",
 *                          "schema" =  {"properties" = Vote::API_DEL_PLACE},
 *                      },
 *                  },
 *             },
 *         },
 *         "add_place"={
 *             "method"="PUT",
 *             "path"="/votes/{id}/add_place",
 *             "controller"=AddPlaceVoteController::class,
 *             "swagger_context"={
 *                 "summary": "Ajouter un restaurant à un vote",
 *                 "description": "
 *    Permet d'ajouter un restaurant à la liste des restaurants disponible pour un vote
 *    Si c'est le premier restaurant du vote, le vote passe en état actif
 *    Un jeton d'authentification JWT est requis",
 *                 "parameters"={
 *                      {
 *                          "name"="vote",
 *                          "in"="body",
 *                          "required"=true,
 *                          "description"="Ajoute un restaurant à la collection",
 *                          "schema" =  {
 *                              "properties" = {
 *                                  "place_id": {"type": "integer"},
 *                                  "vote_id": {"type": "integer"}
 *                              },
 *                          },
 *                      },
 *                  },
 *                 "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un boolean et le restaurant",
 *                          "schema" =  {"properties" = Vote::API_ADD_PLACE},
 *                      },
 *                  },
 *             },
 *         },
 *     },
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"user": "exact", "url"}
 * )
 */
class Vote
{
    public const API_VOTE = [
        'id' => ['type' => 'integer'],
        'userId' => ['type' => 'integer'],
        'title' => ['type' => 'string'],
        'date' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'end_date' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'url' => ['type' => 'string'],
        'updatedAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'createdAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'active' => ['type' => 'boolean'],
        'votes' => [
            'type' => 'array',
            'items' => [
                'properties' => [
                    'pseudo' => ['type' => 'string'],
                    'email' => ['type' => 'string'],
                ],
            ],
        ],
    ];

    public const API_CREATE_VOTE = [
        'date' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'end_date' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'title' => ['type' => 'string'],
    ];

    public const API_VOTE_CREATED = [
        'id' => ['type' => 'integer'],
        'title' => ['type' => 'string'],
        'date' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'end_date' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'url' => ['type' => 'string'],
        'active' => ['type' => 'boolean'],
        'createdAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'updatedAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'voices' => [
            'type' => 'array',
            'items' => [
                'type' => 'string'
            ],
        ],
        'places' => [
            'type' => 'array',
            'items' => [
                'type' => 'string'
            ],
        ],
        'pseudo' => ['type' => 'string'],
        'email' => ['type' => 'string'],
    ];

    public const API_ADD_PLACE = [
        'added' => ['type' => 'boolean'],
        'place' => [
            'type' => 'array',
            'items' => [
                'properties' => Place::API_PLACE

            ]

        ]
    ];

    public const API_DEL_PLACE = [
        'deleted' => ['type' => 'boolean'],
        'place' => [
            'type' => 'array',
            'items' => [
                'properties' => Place::API_PLACE

            ]

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
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Length(
     *     min= 4,
     *     max= 50,
     *     minMessage="Minimum 4 caracters",
     *     maxMessage="Maximum 50 caracters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="ce champs n'est pas une date")
     * @Assert\NotBlank(message="ce champs est obligatoire")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="ce champs n'est pas une date")
     * @Assert\NotBlank(message="ce champs est obligatoire")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="ce champs est obligatoire")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voice", mappedBy="vote", cascade={"persist"})
     */
    private $voices;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Place", inversedBy="votes")
     */
    private $places;

    public function __construct()
    {
        $this->voices = new ArrayCollection();
        $this->places = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
        $url = '';
        $value = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        for ($i = 0; $i < 5; $i++) {
            $url .= $value[array_rand($value)];
        }
        $this->setUrl($url);
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Get total voices per vote
     * @return array
     */
    public function getTotalVoiceForEachPlace(): array
    {
        $places = $this->places;
        $voices = $this->voices;
        $totalVotes = [];
        foreach ($places as $place) {
            $total = 0;
            foreach ($voices as $voice) {
                if ($voice->getVote()->getId() === $this->getId() && $voice->getPlace()->getId() === $place->getId()) {
                    $total++;
                }
            }
            $totalVotes[$place->getId()] = $total;
        }
        return $totalVotes;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate($endDate): self
    {
        if ($endDate > $this->date) {
            $this->endDate = $this->date;
        } else {
            $this->endDate = $endDate;
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive($active): self
    {
        $this->active = $active;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Voice[]
     */
    public function getVoices(): Collection
    {
        return $this->voices;
    }

    public function addVoice(Voice $voice): self
    {
        if (!$this->voices->contains($voice)) {
            $this->voices[] = $voice;
            $voice->setVote($this);
        }

        return $this;
    }

    public function removeVoice(Voice $voice): self
    {
        if ($this->voices->contains($voice)) {
            $this->voices->removeElement($voice);
            // set the owning side to null (unless already changed)
            if ($voice->getVote() === $this) {
                $voice->setVote(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Place[]
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Place $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places[] = $place;
        }

        return $this;
    }

    public function removePlace(Place $place): self
    {
        if ($this->places->contains($place)) {
            $this->places->removeElement($place);
        }

        return $this;
    }

    public function getPseudo()
    {
        return $this->getUser()->getPseudo();
    }

    public function getEmail()
    {
        return $this->getUser()->getEmail();
    }
}
