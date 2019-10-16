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
 *         "get",
 *         "post",
 *         "get_places"={
 *             "method"="POST",
 *             "path"="/votes/places",
 *             "controller"=GetPlacesVoteController::class,
 *         },
 *         "get_myvotes"={
 *             "method"="GET",
 *             "path"="/votes/mines",
 *             "controller"=GetMyVoteController::class,
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/votes/delete",
 *             "controller"=DelVoteController::class,
 *         },
 *
 *     },
 *     itemOperations={
 *         "get",
 *         "delete_place"={
 *             "method"="PUT",
 *             "path"="/votes/{id}/del_place",
 *             "controller"=DelPlaceVoteController::class,
 *         },
 *         "add_place"={
 *             "method"="PUT",
 *             "path"="/votes/{id}/add_place",
 *             "controller"=AddPlaceVoteController::class,
 *         },
 *     },
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"user": "exact", "url"}
 * )
 */
class Vote
{
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
     * @ORM\OneToMany(targetEntity="App\Entity\Voice", mappedBy="vote", cascade={"persist", "remove"})
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
}
