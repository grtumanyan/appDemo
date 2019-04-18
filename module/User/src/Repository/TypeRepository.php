<?php
namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\Type;

/**
 * This is the custom repository class for User entity.
 */
class TypeRepository extends EntityRepository
{
    /**
     * Retrieves all users in descending dateCreated order.
     * @return Query
     */
    public function findAllTypes()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('m')
            ->from(Type::class, 'm')
            ->orderBy('m.id', 'DESC');

        return $queryBuilder->getQuery();
    }
}