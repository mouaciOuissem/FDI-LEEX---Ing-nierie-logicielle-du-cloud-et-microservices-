<?php

namespace App\Entity;

use Vich\Uploadable;
use Vich\UploadableField;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @Vich\Uploadable
 * VideoFormat
 * @ORM\Table(name="video_format", indexes={@ORM\Index(name="fk_video_format_video1_idx", columns={"video_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\VideoFormatRepository")
 */
class VideoFormat
{
    /**
     * @var int
     * @Groups({"Video:Read"})
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Groups({"Video:Read"})
     * @ORM\Column(name="format", type="string", length=45, nullable=true)
     */
    private $format;

    /**
     * @Vich\UploadableField(mapping="video_mapping", fileNameProperty="file")
     */
    public ?File $formatFile = null;

    /**
     * 
     * @var string
     * @Groups({"Video:Read"})
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var \Video
     * @Groups({"VideoRead"})
     * @ORM\ManyToOne(targetEntity=Video::class, inversedBy="videoFormats",cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="video_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $video;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;
        
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

    public function setFormatFile(?File $formatFile = null): void
    {
        $this->formatFile =  $formatFile;
        if (null !== $formatFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFormatFile(): ?File
    {
        return $this->formatFile;
    }
}
