<?php
namespace User\Service;

use User\Entity\Main;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class PostManager
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
    public function addPost($data)
    {
        // Create new User entity.
        $post = new Main();
        $post->setText($data['text']);
        $post->setTitle($data['title']);
        $post->setType($data['type']);
        if($data['lang'] == 2){
            $post->setLang('hy');
        }else{
            $post->setLang('en');
        }
        $post->setImage($data['image']);
        $post->setFile($data['file']);
        $currentDate = date('Y-m-d H:i:s');
        $post->setDateCreated($currentDate);

        // Add the entity to the entity manager.
        $this->entityManager->persist($post);
                       
        // Apply changes to database.
        $this->entityManager->flush();
        
        return $post;
    }
    
    /**
     * This method updates data of an existing user.
     */
    public function updatePost($post, $data)
    {
        $post->setText($data['text']);
        $post->setTitle($data['title']);
        if($data['image']['name'] != ''){$post->setImage($data['image']);}
        if($data['file']['name'] != ''){$post->setFile($data['file']);}

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method updates data of an existing user.
     */
    public function deletePost($post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return true;
    }
}

