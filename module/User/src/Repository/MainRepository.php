<?php
namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\Main;

/**
 * This is the custom repository class for User entity.
 */
class MainRepository extends EntityRepository
{
    /**
     * Retrieves all users in descending dateCreated order.
     * @return Query
     */
    public function findAllPosts()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('m')
            ->from(Main::class, 'm')
            ->orderBy('m.dateCreated', 'DESC');

        return $queryBuilder->getQuery();
    }

    /**
     * Retrieves all users in descending dateCreated order.
     * @return Query
     */
    public function findAllPostsByTypeId($id)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('m')
            ->from(Main::class, 'm')
            ->where('m.type = ?1')
            ->orderBy('m.dateCreated', 'DESC')
            ->setParameter(1, $id);

        return $queryBuilder->getQuery();
    }
}