<?php

namespace App\Entity;

use App\Repository\BasketPositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=BasketPositionRepository::class)
 */

#[ApiResource (
   attributes: ["security" => "is_granted('ROLE_USER')"],
   collectionOperations: [
    "get" => ["security" => "is_granted('ROLE_USER')"],
    "post" => ["security" => "is_granted('ROLE_USER')"],
    ],
   itemOperations: [
    "get" => ["security" => "is_granted('ROLE_USER')"],
    "delete" => ["security" => "is_granted('ROLE_USER')"],
    ],
    denormalizationContext: ['groups' => ['write']],
)]

class BasketPosition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $sessionID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["write"])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $title;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(["write"])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?int $quantity;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(["write"])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?float $price;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class, inversedBy="и?basketPosition")
     */

    #[ApiSubresource(maxDepth: 1,)]
    private ?Catalog $catalog;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="basketposition")
     */

    #[ApiSubresource(maxDepth: 1,)]
    private ?Order $orderN;

    /**
     * @ORM\ManyToMany(targetEntity=Ingridient::class)
     */
    #[Groups(["write"])]
    #[ApiSubresource(maxDepth: 1,)]
    private  $Ingr;

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
