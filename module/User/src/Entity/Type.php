<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a role.
 * @ORM\Entity(repositoryClass="\User\Repository\TypeRepository")
 * @ORM\Table(name="type")
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="text")
     */
    protected $text;
    
    /**
     * Returns role ID.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets role ID. 
     * @param int $id    
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getText()
    {
        return $this->text;
    }
    
    public function setText($text)
    {
        $this->text = $text;
    }
}



