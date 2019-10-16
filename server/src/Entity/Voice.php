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

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoiceRepository")
 * @ApiResource(
 *     collectionOperations={
 *         "count_all"={
 *             "method"="GET",
 *             "path"="/voices/count/all",
 *             "controller"=CountAllVoiceByPlaceController::class,
 *         },
 *         "get_all"={
 *             "method"="GET",
 *             "path"="/voices/get/all",
 *             "controller"=GetAllVoiceByVoteController::class,
 *         },
 *         "add_voice"={
 *             "method"="POST",
 *             "path"="/voices/add",
 *             "controller"=AddVoiceController::class,
 *         },
 *         "del_voice"={
 *             "method"="DELETE",
 *             "path"="/voices/del",
 *             "controller"=DelVoiceController::class,
 *         },
 *     },
 *     itemOperations={"get"},
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"email", "pseudo", "vote", "place"}
 * )
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
