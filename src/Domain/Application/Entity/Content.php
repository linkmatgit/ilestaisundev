<?php

namespace App\Domain\Application\Entity;

use App\Domain\Application\Repository\ContentRepository;
use App\Domain\Auth\Entity\Users;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 */
class Content
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private string $title ='';

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min='3', minMessage="Le message doit contenir minimum 3 caractere", max='254', maxMessage="Le message doit contenir mamimum 254 Caractere")
     */
    private string $slug ='';

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="boolean" , options={"default": 0})
     */
    private bool $online;

    /**
     * @ORM\Column(type="boolean" , options={"default": 0})
     */
    private bool $accepted;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Auth\Entity\Users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private ?Users $author = null;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }
}
