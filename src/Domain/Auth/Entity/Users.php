<?php

namespace App\Domain\Auth\Entity;

use App\Domain\Auth\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private string $title = '';
    /**
     * @ORM\Column(type="string")
     */
    private string $content = "";
    /**
     * @ORM\Column(type="datetime_imutable")
     */
    private \DateTimeInterface $registedAt;

    /**
     * @ORM\Column(type="datetime_imutable")
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Users
     */
    public function setTitle(string $title): Users
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Users
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getRegistedAt(): \DateTimeInterface
    {
        return $this->registedAt;
    }

    /**
     * @param \DateTimeInterface $registedAt
     * @return Users
     */
    public function setRegistedAt(\DateTimeInterface $registedAt): self
    {
        $this->registedAt = $registedAt;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     * @return Users
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }
}
