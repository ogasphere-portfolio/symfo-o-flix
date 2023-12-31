<?php

namespace App\Entity;


use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TvShowRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TvShowRepository::class)
 */
class TvShow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"api_tvshow_browse", "api_category_browse"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * 
     * @Groups({"api_tvshow_browse", "api_category_browse"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * 
     * @Groups({"api_tvshow_browse", "api_category_browse"})
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbLikes;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * 
     * @Groups("api_tvshow_browse")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * 
     * @Groups("api_tvshow_browse")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="tvShow")
     * 
     * @Groups("api_tvshow_browse")
     */
    private $seasons;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="tvShows")
     * 
     * @Groups("api_tvshow_browse")
     */
    private $categories;

    
    /**
     * @ORM\OneToMany(targetEntity=Play::class, mappedBy="tvshow")
     */
    private $plays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->plays = new ArrayCollection();

        $this->createdAt = new DateTimeImmutable();
        $this->nbLikes = 0;
    }

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

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setNbLikes(int $nbLikes): self
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Season[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setTvShow($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getTvShow() === $this) {
                $season->setTvShow(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|Play[]
     */
    public function getPlays(): Collection
    {
        return $this->plays;
    }

    public function addPlay(Play $play): self
    {
        if (!$this->plays->contains($play)) {
            $this->plays[] = $play;
            $play->setTvshow($this);
        }

        return $this;
    }

    public function removePlay(Play $play): self
    {
        if ($this->plays->removeElement($play)) {
            // set the owning side to null (unless already changed)
            if ($play->getTvshow() === $this) {
                $play->setTvshow(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->title;
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
}
