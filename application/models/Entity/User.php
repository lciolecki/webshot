<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="users", 
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UNIQ_email", columns={"email"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="Repository\Users")
 * @ORM\HasLifecycleCallbacks
 */
class User extends \Entity\AbstractEntity
{
    const HASH_LEN = 32; 
    const SALT_LEN = 16;
    
    const PASSWORD_HASH_ALGORITHM = 'md5';
    
    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_INACTIVE = 3;
    const STATUS_DELETED = 4;
    
    const ROLE_GUEST = 'guest';
    const ROLE_ADMIN = 'admin';
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first_last_name", type="string", length=255, nullable=true)
     */
    private $firstLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=16, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status = self::STATUS_NEW;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=false)
     */
    private $hash;
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=false)
     */
    private $password;

    /**
     * Instancja konstruktora
     * 
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data);
    }
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** 
     * @ORM\PrePersist 
     */
    public function preInsert()
    {
        if (!$this->password) {
            $this->setPassword(\Extlib\Generator::generate());
        }
        
        $this->hash = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'hash', self::HASH_LEN);
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->salt = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'salt', self::SALT_LEN);
        $this->password = \hash(self::PASSWORD_HASH_ALGORITHM, $password . $this->salt);

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set firstLastName
     *
     * @param string $firstLastName
     * @return User
     */
    public function setFirstLastName($firstLastName)
    {
        $this->firstLastName = $firstLastName;
    
        return $this;
    }

    /**
     * Get firstLastName
     *
     * @return string 
     */
    public function getFirstLastName()
    {
        return $this->firstLastName;
    }
}