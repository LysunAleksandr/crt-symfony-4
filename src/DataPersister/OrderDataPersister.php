<?php


namespace App\DataPersister;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\BasketPositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class OrderDataPersister implements DataPersisterInterface
{

    private $entityManager;
    private $basketPositionRepository;
    private $tokenInterface;

    public function __construct(EntityManagerInterface $entityManager, BasketPositionRepository $basketPositionRepository, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->basketPositionRepository = $basketPositionRepository;
        $this->tokenInterface = $tokenStorage->getToken();

   }

    public function supports($data): bool
    {
        return $data instanceof Order;
    }
    /**
     * @param Order $data
     */
    public function persist($data)
    {
        $sessionId = $data->getSessionID();
        $basketPositions = $this->basketPositionRepository->findBy(['sessionID' => $sessionId, 'orderN' => null ]);
        foreach ($basketPositions  as $basketPosition) {
            $data->addBasketposition($basketPosition);
        }
        $data->setCreatedAtValue();
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}