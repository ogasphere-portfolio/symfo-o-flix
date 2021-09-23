<?php

namespace App\Entity;

use App\Repository\PlayRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayRepository::class)
 */
class Play
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $creditOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="plays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Charact;

    /**
     * @ORM\ManyToOne(targetEntity=TvShow::class, inversedBy="plays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tvShow;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getCreditOrder(): ?string
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(?string $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

        return $this;
    }

    public function getCharact(): ?Character
    {
        return $this->Charact;
    }

    public function setCharact(?Character $Charact): self
    {
        $this->Charact = $Charact;

        return $this;
    }

    public function getTvShow(): ?TvShow
    {
        return $this->tvShow;
    }

    public function setTvShow(?TvShow $tvShow): self
    {
        $this->tvShow = $tvShow;

        return $this;
    }
}
