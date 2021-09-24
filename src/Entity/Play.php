<?php

namespace App\Entity;

use App\Repository\RolePlayRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RolePlayRepository::class)
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
     * @ORM\Column(type="integer")
     */
    private $creditOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="rolePlays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personage;

    /**
     * @ORM\ManyToOne(targetEntity=TvShow::class, inversedBy="rolePlays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tvshow;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditOrder(): ?int
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(int $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

        return $this;
    }

    public function getPersonage(): ?Character
    {
        return $this->personage;
    }

    public function setPersonage(?Character $personage): self
    {
        $this->personage = $personage;

        return $this;
    }

    public function getTvshow(): ?TvShow
    {
        return $this->tvshow;
    }

    public function setTvshow(?TvShow $tvshow): self
    {
        $this->tvshow = $tvshow;

        return $this;
    }
}
