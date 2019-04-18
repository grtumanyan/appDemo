<?php
namespace User\Service;

use User\Entity\Type;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class TypeManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * This method adds a new post.
     */
    public function addType($data)
    {
        // Create new User entity.
        $type = new Type();
        $type->setText($data['text']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($type);
                       
        // Apply changes to database.
        $this->entityManager->flush();
        
        return $type;
    }
    
    /**
     * This method updates data of an existing user.
     */
    public function updateType($type, $data)
    {
        $type->setText($data['text']);
        
        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method updates data of an existing user.
     */
    public function deleteType($type)
    {
        $this->entityManager->remove($type);
        $this->entityManager->flush();

        return true;
    }
}

