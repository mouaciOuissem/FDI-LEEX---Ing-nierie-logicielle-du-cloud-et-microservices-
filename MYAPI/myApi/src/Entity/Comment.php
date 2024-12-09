<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Comment
 * @ApiResource(   
 * normalizationContext ={ 
 *                        "groups" = { "Comment:Read"}
 *                      },
 * denormalizationContext ={ 
 *                      "groups"= { "Comment:Write"}
 *                      },
 * collectionOperations={
 *                            "get_videos_comments" =
 *                              {
 *                                "method" = "GET",
 *                                "route_name" = "get_videos_comments",
 *                                "openapi_context" = {
 *                                                      "parameters" = {
 *                                                                     {
 *                                                                       "name" = "id",
 *                                                                       "in" = "path",
 *                                                                       "description" = "video id",
 *                                                                       "required" = true,
 *                                                                       "type" : "array",
 *                                                                       "items" : {
 *                                                                                   "type" : "integer"
 *                                                                                    }
 *                                                                           }
 *                                                                            }
 *                                                       }       
 *                              },
 * 
 *                             "post_videos_comments" =
 *                              {
 *                                "method" = "POST",
 *                                "route_name" = "post_videos_comments"  ,
 *                                "openapi_context" = {
 *                                                      "parameters" = {
 *                                                                     {
 *                                                                       "name" = "id",
 *                                                                       "in" = "path",
 *                                                                       "description" = "video id",
 *                                                                       "required" = true,
 *                                                                       "type" : "array",
 *                                                                       "items" : {
 *                                                                                   "type" : "integer"
 *                                                                                    }
 *                                                                           }
 *                                                                            },
 *   "requestBody": {
 *                     "content": {
 *                         "application/ld+json": {
 *                             "schema": {
 *                                 "type": "object",
 *                                 "properties": {
 *                                     "body": {"type": "string", "example": "Ceci est un commentaire"}
 *                                     
 *                                 },
 *                             },
 *                         },
 *                     },
 *                 }, 
 *                                                       }  ,
 * 
 *                                 "denormalization_context" = { "groups" = { "Comment:Post" } }          
 *                              },
 *                      },
 * itemOperations = { 
 *                                     "get" = { "method" = "GET"}
 *                                    })
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="fk_comment_user1_idx", columns={"user_id"}), @ORM\Index(name="fk_comment_video1_idx", columns={"video_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var int
     * @Groups({"Comment:Read"})
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     * @Groups({"Comment:Read","Comment:Write" })
     * @ORM\Column(name="body", type="text", length=0, nullable=true)
     */
    private $body;

    /**
     * @var \User
     * @Groups({"Comment:Read","Comment:Write" })
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @var \Video
     * @Groups({"Comment:Read","Comment:Write" })
     * @ORM\ManyToOne(targetEntity=Video::class, inversedBy="comments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="video_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $video;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

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

    public function getVideo()
    {
        return $this->video;
    }

    public function setVideo(?Video $video): self
    {
        $this->video = $video;

        return $this;
    }



}
