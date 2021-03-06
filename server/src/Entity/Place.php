<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *             "swagger_context"={
 *                 "summary": "Récupérer une collection de restaurants",
 *                 "description": "
 *    Permet de récupérer la liste des restaurant dans une certaine zone délimité pour 4 coordonées",
 *                 "parameters"={
 *                      {"name"="lat[gte]","in"="query","required"=true,"type": "number", "description": "latitude la plus petite"},
 *                      {"name"="lat[lte]","in"="query","required"=true,"type": "number", "description": "latitude la plus grande"},
 *                      {"name"="lng[gte]","in"="query","required"=true,"type": "number", "description": "longitude la plus petite"},
 *                      {"name"="lng[lte]","in"="query","required"=true,"type": "number", "description": "longitude la plus grande"},
 *                  },
 *                  "responses"={
 *                      "200" = {
 *                          "description" = "Retourne un tableau contenant tout les restaurants dans cette zone",
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
 *     },
 *     itemOperations={
 *         "get"={
 *             "swagger_context"={
 *                 "summary": "Récupérer un restaurant",
 *                 "description": "
 *    Filtre par Id",
 *             },
 *         },
 *     },
 * )
 * @ApiFilter(
 *     RangeFilter::class, properties={"lat", "lng"}
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"votes"}
 * )
 */
class Place implements \JsonSerializable
{
    public const API_PLACE = [
        'id' => ['type' => 'integer'],
        'name' => ['type' => 'string'],
        'lat' => ['type' => 'integer'],
        'lng' => ['type' => 'number'],
        'type' => ['type' => 'string'],
        'address' => ['type' => 'string'],
        'city' => ['type' => 'string'],
        'phone' => ['type' => 'string'],
        'email' => ['type' => 'string'],
        'website' => ['type' => 'string'],
        'opening_hours' => ['type' => 'string'],
        'cuisine' => ['type' => 'string'],
        'createdAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'updatedAt' => [
            'type' => 'string',
            'format' => 'date-time'
        ],
        'votes' => [
            'type' => 'array',
            'items' => [
                'type' => 'string'
            ]
        ],
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingHours;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cuisine;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public $ref;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voice", mappedBy="place")
     */
    private $voices;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vote", mappedBy="places")
     */
    private $votes;

    public function __construct()
    {
        $this->voices = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return ($this->getName() . ' ( id : ' . $this->getId() . ' )');
    }

    public function getRef()
    {
        return ($this->getName() . ' ( id : ' . $this->getId() . ' )');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getOpeningHours(): ?string
    {
        return $this->openingHours;
    }

    public function setOpeningHours(?string $openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(?string $cuisine): self
    {
        $this->cuisine = $cuisine;

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
            $voice->setPlace($this);
        }

        return $this;
    }

    public function removeVoice(Voice $voice): self
    {
        if ($this->voices->contains($voice)) {
            $this->voices->removeElement($voice);
            // set the owning side to null (unless already changed)
            if ($voice->getPlace() === $this) {
                $voice->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->addPlace($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            $vote->removePlace($this);
        }

        return $this;
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
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'lat' => $this->getLat(),
            'lng' => $this->getLng(),
            'type' => $this->getType(),
            'address' => $this->getAddress(),
            'city' => $this->getCity(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'website' => $this->getWebsite(),
            'opening_hours' => $this->getOpeningHours(),
            'cuisine' => $this->getCuisine(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'votes' => $this->getVotes(),
        ];
    }
}
