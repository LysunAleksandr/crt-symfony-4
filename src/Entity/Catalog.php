<?php

namespace App\Entity;

use App\Repository\CatalogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
/**
 * @ORM\Entity(repositoryClass=CatalogRepository::class)
 */
#[ApiResource (
   collectionOperations: [
    "get",
    "post" => ["security" => "is_granted('ROLE_ADMIN')"],
   ],
   itemOperations: [
    "get",
    "delete" => ["security" => "is_granted('ROLE_ADMIN')"],
   ],
)]
class Catalog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Assert\NotBlank]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    private $title;

    /**
     * @ORM\Column(type="float")
     */
    #[Assert\NotBlank]
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photoFilename;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Ingridient::class)
     */
    private $Ingr;



    public function __construct()
    {
        $this->basketPositions = new ArrayCollection();
        $this->Ingr = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }

    public function setPhotoFilename(?string $photoFilename): self
    {
        $this->photoFilename = $photoFilename;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;// . ' ' . $this->description;
    }

     /**
     * @return Collection|Ingridient[]
     */
    public function getIngr(): Collection
    {
        return $this->Ingr;
    }

    public function addIngr(Ingridient $ingr): self
    {
        if (!$this->Ingr->contains($ingr)) {
            $this->Ingr[] = $ingr;
        }

        return $this;
    }

    public function removeIngr(Ingridient $ingr): self
    {
        $this->Ingr->removeElement($ingr);

        return $this;
    }


}
