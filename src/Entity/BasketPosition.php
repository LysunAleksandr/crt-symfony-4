<?php

namespace App\Entity;

use App\Repository\BasketPositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BasketPositionRepository::class)
 */
class BasketPosition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sessionID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class, inversedBy="Ð¸ÑbasketPosition")
     */
    private $catalog;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="basketposition")
     */
    private $orderN;

    /**
     * @ORM\ManyToMany(targetEntity=Ingridient::class)
     */
    private $Ingr;

    public function __construct()
    {
        $this->Ingr = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionID(): ?string
    {
        return $this->sessionID;
    }

    public function setSessionID(string $sessionID): self
    {
        $this->sessionID = $sessionID;

        return $this;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getTotalPrice(): ?float
    {
        return $this->price * $this->quantity;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCatalog(): ?Catalog
    {
        return $this->catalog;
    }

    public function setCatalog(?Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }
    public function __toString(): string
    {
        return 'Pizza: ' . $this->title . ' |    quantity: ' . $this->quantity . ' |      price: ' . $this->price . ' |   total price: ' . $this->getTotalPrice();
    }

    public function getOrderN(): ?Order
    {
        return $this->orderN;
    }

    public function setOrderN(?Order $orderN): self
    {
        $this->orderN = $orderN;

        return $this;
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
