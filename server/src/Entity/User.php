<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 *     itemOperations={
 *         "get"={
 *             "swagger_context"={
 *                 "summary": "Récupérer un utilisateur",
 *                 "description": "
 *    Filtre par Id",
 *             },
 *         }
 *     },
 *     collectionOperations={
 *         "get"={
 *             "swagger_context"={
 *                 "summary": "Récupérer une collection d'utilisateurs",
 *                 "description": "
 *    Filtre par email ou pseudo",
 *             },
 *         },
 *         "login_check"={
 *             "method"="POST",
 *             "path"="/login_check",
 *             "swagger_context"={
 *                 "summary": "Récupérer un token",
 *                 "description": "
 *   Permet d'obtenir un token JWT qui expire tout les jours à 00h",
 *                 "parameters"={
 *                      {
 *                          "name"="vote",
 *                          "in"="body",
 *                          "required"=true,
 *                          "description"="",
 *                          "schema" =  {
 *                              "properties" = {
 *                                  "password": {"type": "string"},
 *                                  "username": {"type": "string"}
 *                              },
 *                          },
 *                      },
 *                  },
 *                  "responses"={
 *                      "200" = {
 *                          "description" = "Retourne le token JWT",
 *                          "schema" =  {"properties": {"token": {"type": "string"}}},
 *                      },
 *                  },
 *             },
 *         },
 *         "post"={
 *             "swagger_context"={
 *                 "summary": "Créer un utilisateur",
 *                 "description": "",
 *                 "parameters"={
 *                      {
 *                          "name"="user",
 *                          "in"="body",
 *                          "required"=true,
 *                          "description"="",
 *                          "schema" =  {
 *                              "properties" = {
 *                                  "email": {"type": "string"},
 *                                  "password": {"type": "string"},
 *                                  "password_repeat": {"type": "string"},
 *                                  "pseudo": {"type": "string"},
 *                              },
 *                          },
 *                      },
 *                  },
 *                 "responses"={
 *                      "201" = {
 *                          "description" = "",
 *                          "schema" =  {
 *                              "properties" = {
 *                                  "exist": {
 *                                      "type": "array",
 *                                      "items": {"properties": {"exist": {"type": "boolean"}}},
 *                                  },
 *                                  "created": {"type": "boolean"},
 *                                  "id": {"type": "integer"},
 *                                  "email": {"type": "string"},
 *                                  "username": {"type": "string"},
 *                                  "roles": {
 *                                      "type": "array",
 *                                      "items": {"type": "string"},
 *                                  },
 *                                  "password": {"type": "string"},
 *                                  "pseudo": {"type": "string"},
 *                                  "votes": {
 *                                      "type": "array",
 *                                      "items": {"type": "string"},
 *                                  },
 *                              },
 *                          },
 *                      },
 *                  },
 *             },
 *         },
 *     },
 * )
 * @UniqueEntity("email", message="Cet email est déjà utilisé par un autre utilisateur")
 * @UniqueEntity("pseudo", message="Ce pseudo est déjà utilisé par un autre utilisateur")
 * @ApiFilter(
 *     SearchFilter::class, properties={"email": "exact", "pseudo": "exact"}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="ce champs est obligatoire")
     * @Assert\Email(message="Cet email n'est pas valide")
     * @Assert\Length(
     *     min= 4,
     *     max= 50,
     *     minMessage="Minimum 4 caracters",
     *     maxMessage="Maximum 50 caracters"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="ce champs est obligatoire")
     * @Assert\Length(
     *     min= 4,
     *     max= 50,
     *     minMessage="Minimum 4 caracters",
     *     maxMessage="Maximum 50 caracters"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="ce champs est obligatoire")
     * @Assert\Length(
     *     min= 4,
     *     max= 50,
     *     minMessage="Minimum 4 caracters",
     *     maxMessage="Maximum 50 caracters"
     * )
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="user", cascade={"persist"})
     */
    private $votes;

    private $exist;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->created = true;
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getExist()
    {
        return ['exist' => true];
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $vote->setUser($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getUser() === $this) {
                $vote->setUser(null);
            }
        }

        return $this;
    }
}
