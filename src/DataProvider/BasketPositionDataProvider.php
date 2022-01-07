<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\BasketPosition;
use App\Repository\BasketPositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BasketPositionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $entityManager;
    private $basketPositionRepository;
    private $user;

    public function __construct(EntityManagerInterface $entityManager, BasketPositionRepository $basketPositionRepository,TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->basketPositionRepository = $basketPositionRepository;
        $this->user = $tokenStorage->getToken()->getUser()->getUserIdentifier();

    }
    /**
     * @param array<string, mixed> $context
     *
     * @throws \RuntimeException
     *
     * @return iterable<BasketPosition>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        try {
            $collection = $this->basketPositionRepository->findBy(['sessionID' => $this->user, 'orderN' => null ]);
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Unable to retrieve top books from external source: %s', $e->getMessage()));
        }


            return $collection;

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return BasketPosition::class === $resourceClass;
    }
}