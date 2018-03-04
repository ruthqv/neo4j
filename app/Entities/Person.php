<?php

namespace App\Entities;

use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 *
 * @OGM\Node(label="Person")
 */
class Person extends SerializedEntity
{
    /**
     * @var int
     * 
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @var string
     * 
     * @OGM\Property(type="string")
     */
    protected $name;

    /**
     * @var int
     * 
     * @OGM\Property(type="int")
     */
    protected $born;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getBorn()
    {
        return $this->born;
    }

    /**
     * @param int $born
     */
    public function setBorn($born)
    {
        $this->born = $born;
    }
    
   /**
     * @var Movie[]|Collection
     * 
     * @OGM\Relationship(type="ACTED_IN", direction="OUTGOING", collection=true, mappedBy="actors", targetEntity="Movie")
     */
    protected $movies;

    public function __construct()
    {
        $this->movies = new Collection();
    }

    // other code

    /**
     * @return Movie[]|Collection
     */
    public function getMovies()
    {
        return $this->movies;
    }


}