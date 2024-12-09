<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * User
 * @ApiResource( normalizationContext={ "groups"={"User:Read"} },
 * denormalizationContext={ "groups"={"User:Write"} },
 *             collectionOperations={
 *                                  "get"={ "method" = "GET",
 *                                          "path" = "/users",                          
 *                                          },
 *                                  "post"={ "method" = "POST",
 *                                          "path" = "/users"
 *                                          },
 *                                  
 *                                   },
 *               itemOperations={ "get"={ "method" = "GET",
 *                                          "path" = "/users/{id}",                          
 *                                          },
 *                                  "put"={ "method" = "PUT",
 *                                          "path" = "/users/{id}",
 *                                          },
 *                                  "delete"={ "method" = "DELETE",
 *                                          "path" = "/users/{id}",
 *                                          },
 *                                  "me"={ "method" = "GET",
 *                                          "route_name" = "me", 
 *                                          "openapi_context" = {
 *                                                      "parameters" = {
 *                                                                     {
 *                                                                       "name" = "id",
 *                                                                       "in" = "path",
 *                                                                       
 *                                                                       "required" = false,
 *                                                                       "type" : "array",
 *                                                                       "items" : {
 *                                                                                   "type" : "integer"
 *                                                                                    }
 *                                                                           }
 *                                                                            }
 *                                                       }                          
 *                                          },
 *                                   }
 *                      
 * )
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"}), @ORM\UniqueConstraint(name="username_UNIQUE", columns={"username"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    public function __construct(){
        $this->createdAt = new DateTime();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }
    /**
     * @var int
     * @groups({"User:Read","VideoFormat:Read","Video:Read","Comment:Read"})
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Groups({"User:Read","User:Write","Video:Read","Video:Write","VideoFormat:Read","Comment:Read"})
     * @ORM\Column(name="username", type="string", length=45, nullable=false)
     * @Assert\NotBlank(
     *     message = "The field must no be empty."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-zA-Z0-9_-]+$/i",
     *     htmlPattern = "[a-zA-Z0-9_-]+"
     * )
     */
    private $username;

    /**
     * @var string
     * @Groups({"User:Read","User:Write","Video:Read","Video:Write","VideoFormat:Read","Comment:Read"})
     * @ORM\Column(name="email", type="string", length=45, nullable=false)
     * @Assert\NotBlank(
     *     message = "The field must no be empty."
     * )
     * @Assert\Email(
     *     message = "The value '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @var string|null
     * @Groups({"User:Read","User:Write","Video:Read","Video:Write","VideoFormat:Read","Comment:Read"})
     * @ORM\Column(name="pseudo", type="string", length=45, nullable=true)
     */
    
    private $pseudo;

    /**
     *
     * @var string
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     *  @Groups({"User:Read","Video:Read","VideoFormat:Read","Comment:Read"})
     * @ORM\Column(type="json")
     * @Groups({"Video:Read","VideoFormat:Read"})
     */
    private $roles = [];


    /**
     * @var \DateTime
     * @Groups({"User:Read","User:Write","Video:Read","VideoFormat:Read","Comment:Read"})
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * 
     */
    private $createdAt;

    /**
     * @SerializedName("password")
     * @Groups({"User:Write"})
     */
    private $plainPassword;

    /**
     * @ApiSubresource()
     * 
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="user",cascade={"persist"})
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user",cascade={"persist"})
     */
    private $comments;



    public function getId(): ?int
    {
        return $this->id;
    }

     /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    
 /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;    
    }


    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setUser($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUser() === $this) {
                $video->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}



