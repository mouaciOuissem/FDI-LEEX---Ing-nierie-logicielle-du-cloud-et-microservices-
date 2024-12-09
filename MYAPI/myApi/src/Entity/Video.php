<?php

namespace App\Entity;
use DateTime;

use Vich\Uploadable;
use Vich\UploadedField;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @Vich\Uploadable
 * Video
 * @ApiFilter(SearchFilter::class, properties={"name": "partial", "user": "exact", "duration": "partial"})
 * @ApiResource( 
 *normalizationContext ={ 
 *                        "groups" = { "Video:Read"}
 *                      },
 *denormalizationContext ={ 
 *                      "groups"= { "Video:Write"}
 *                      },
 *collectionOperations ={
 *                    "get" =
 *                              {
 *                                "method" = "GET",
 *                                "path" = "/videos" ,
 *                                "normalization_context" = { "groups" = { "Video:Read" }  }  
 *                              },
 *                      "get_users_videos" =
 *                              {
 *                                "method" = "GET",
 *                                "route_name" = "get_users_videos",
 *                                "openapi_context" = {
 *                                                      "parameters" = {
 *                                                                     {
 *                                                                       "name" = "id",
 *                                                                       "in" = "path",
 *                                                                       "description" = "user id",
 *                                                                       "required" = true,
 *                                                                       "type" : "array",
 *                                                                       "items" : {
 *                                                                                   "type" : "integer"
 *                                                                                    }
 *                                                                           }
 *                                                                            }
 *                                                       }       
 *                                   },
 *                      "post_users_videos" =
 *                              {
 *                                "method" = "POST",
 *                                "route_name" = "post_users_videos"  ,
 *                                "deserialize" = false,
 *                                "openapi_context" = {
 *                                                      "parameters" = {
 *                                                                     {
 *                                                                       "name" = "id",
 *                                                                       "in" = "path",
 *                                                                       "description" = "user id",
 *                                                                       "required" = true,
 *                                                                       "type" : "array",
 *                                                                       "items" : {
 *                                                                                   "type" : "integer"
 *                                                                                    }
 *                                                                           }
 *                                                                            },
 *  "requestBody"= {
 *                     "content"= {
 *                         "multipart/form-data"= {
 *                                                      "schema"= {
 *                                                      "type"= "object",
 *                                                      "properties"= {
 *                                                      "name"= {"type"= "string", "example"= "MyVideo"},
 *                                                      "source"= {"type"= "string", "example"= "myfile.mp4", "format" = "binary"},
 *                                                                      },
 *                                                                  },
 *                                                      }
 *                                  }
 *                  },
 *                                                       }  ,
 *                                 "denormalization_context" = { "groups" = { "Video:Post" } }          
 *                              },
 *                      },
 *                   
 *                itemOperations = { 
 *                                 
 *                                   "put" ={ "method"  = "PUT",
 *                                           "path"  = "/videos/{id}",
 *                                           },
 *                                   "delete" ={ "method"  = "DELETE",
 *                                           "path"  = "/videos/{id}",
 *                                           "security" = " object.user === user",
 *                                           "security_message" = "Vous n'avez pas les droits de supprimer la vidéo. Seul l'utilisateur qui la poster peut supprimer."
 *                                           },
 *                                     "get" = { "method" = "GET"},
 * 
 *                                    "patch_videos" =
 *                                                       {  "method" = "PATCH",
 *                                                           "deserialize" = false,
 *                                                           "route_name" = "patch_videos"  ,
 *                                                            "openapi_context" = {
 *                                                                             "parameters" = {
 *                                                                                               {
 *                                                                                                  "name" = "id",
 *                                                                                                  "in" = "path",
 *                                                                                                  "description" = "video id",
 *                                                                                                  "required" = true,
 *                                                                                                  "type" : "array",
 *                                                                                                  "items" : {
 *                                                                                                             "type" : "integer"
 *                                                                                                            }
 *                                                                                                 },                                                                                       
 *                                                                                              } ,
 * "requestBody": {
 *                     "content": {
 *                         "multipart/form-data": {
 *                             "schema": {
 *                                 "type": "object",
 *                                 "properties": {
 *                                     "format": {"type": "string", "example": "720"},
 *                                     "file": {"type": "string", "example": "myfile.mp4", "format" = "binary"},
 *                                 },
 *                             },
 *                         },
 *                     },
 *                 }, 
 *                                                                                   }                                                 
 *                                                                                                  
 *                                                       }
 *                                    }
 * 
 * 
 * )
 * @ORM\Table(name="video", indexes={@ORM\Index(name="fk_video_user_idx", columns={"user_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
{
    public function __construct(){
        $this->createdAt = new DateTime();
        $this->comments = new ArrayCollection();
        $this->videoFormats = new ArrayCollection();
        $this->encoded = false;
    }
    
    /**
     * @var int
     * @Groups({"Video:Read","Comment:Read"})
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Groups({"Video:Read","Video:Write","Comment:Read"})
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var int|null
     * @Groups({"Video:Read","Video:Write","Comment:Read"})
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var string
     * @Groups({"Video:Read","Video:Write","Comment:Read"})
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * Undocumented variable
     * @Vich\UploadableField(mapping="video_mapping", fileNameProperty="source")
     * @var File|null
     */
    private $file;

    /**
     * @var \DateTime
     * @Groups({"Video:Read","Video:Write","Comment:Read"})
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var int
     * @Groups({"Video:Read","Video:Write","Comment:Read"})
     * @ORM\Column(name="view", type="integer", nullable=true)
     */
    private $view;

    /**
     * @var bool
     * @Groups({"Video:Read","Video:Write","Comment:Read"})
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @Groups({"Video:Read","Video:Write","Comment:Read"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="videos",cascade={"persist"})
     *  @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    public $user;

    /**
     * @Groups({"Video:Read"})
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="video")
     */
    private $comments;

    /**
     * @Groups({"Video:Read","Comment:Read"})
     * @ORM\OneToMany(targetEntity=VideoFormat::class, mappedBy="video",cascade={"persist"})
     */
    private $videoFormats;

    /**
     * @Groups({"Video:Read"})
     * @ORM\Column(type="boolean")
     * 
     */
    private $encoded;

    
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): self
    {
        $this->file = $file;
        /*if ($this->file instanceof UploadedFile) {
            $this->setCreatedAt(new \DateTime('now'));
        }*/
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

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(int $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $comment->setVideo($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getVideo() === $this) {
                $comment->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VideoFormat>
     */
    public function getVideoFormats(): Collection
    {
        return $this->videoFormats;
    }

    public function addVideoFormat(VideoFormat $videoFormat): self
    {
        if (!$this->videoFormats->contains($videoFormat)) {
            $this->videoFormats[] = $videoFormat;
            $videoFormat->setVideo($this);
        }

        return $this;
    }

    public function removeVideoFormat(VideoFormat $videoFormat): self
    {
        if ($this->videoFormats->removeElement($videoFormat)) {
            // set the owning side to null (unless already changed)
            if ($videoFormat->getVideo() === $this) {
                $videoFormat->setVideo(null);
            }
        }

        return $this;
    }

    public function isEncoded(): ?bool
    {
        return $this->encoded;
    }

    public function setEncoded(bool $encoded): self
    {
        $this->encoded = $encoded;

        return $this;
    }


}
