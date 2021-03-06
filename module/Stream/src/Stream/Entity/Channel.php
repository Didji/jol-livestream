<?php
namespace Stream\Entity;

use BadMethodCallException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Stream\Repository\ChannelRepository")
 * @ORM\Table(name="channels")
 **/
class Channel
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $externalId;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $jolUser;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        $method = 'get' . ucfirst($property);
        if (!method_exists($this, $method)) {
            throw new BadMethodCallException($method . " n'existe pas.");
        }
        return $this->$method();
    }

    /**
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, mixed $value)
    {
        $method = 'set' . ucfirst($property);
        if (!method_exists($this, $method)) {
            throw new BadMethodCallException($method . " n'existe pas.");
        }
        return $this->$method($value);
    }

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
     * @return int
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return string
     */
    public function getJolUser()
    {
        return $this->jolUser;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int
     * @return Channel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string
     * @return Channel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param int
     * @return Channel
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @param string
     * @return Channel
     */
    public function setJolUser($jolUser)
    {
        $this->jolUser = $jolUser;
        return $this;
    }

    /**
     * @param string
     * @return Channel
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string
     * @return Channel
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
