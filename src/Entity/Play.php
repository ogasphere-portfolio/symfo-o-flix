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
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="plays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $charact;

    /**
     * @ORM\ManyToOne(targetEntity=TvShow::class, inversedBy="plays")
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

    public function getCharact(): ?Character
    {
        return $this->charact;
    }

    public function setCharact(?Character $charact): self
    {
        $this->charact = $charact;

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
