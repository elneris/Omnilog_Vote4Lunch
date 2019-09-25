<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoiceRepository")
 * @ApiResource(
 *     normalizationContext={"groups"="voice:read"},
 *     denormalizationContext={"groups"={"voice:write"}},
 *
 *     collectionOperations={
 *         "get",
 *         "post",
 *     },
 *     itemOperations={
 *         "get",
 *         "delete",
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "vote": "exact",
 *     "place": "exact",
 *     "pseudo": "exact",
 *     })
 */
class Voice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"voice:read", "voice:write"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min= 4,
     *     max= 50,
     *     minMessage="Minimum 4 caracters",
     *     maxMessage="Maximum 50 caracters"
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"voice:read", "voice:write"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(
     *     min= 4,
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
     * @Groups({"voice:read", "voice:write"})
     * @Assert\NotNull()
     */
    private $vote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="voices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"voice:read", "voice:write"})
     * @Assert\NotNull()
     */
    private $place;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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
