<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service", 
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UNIQUE_name", columns={"name"}),
 *      @ORM\UniqueConstraint(name="UNIQUE_hash", columns={"hash"}),
 *      @ORM\UniqueConstraint(name="UNIQUE_secret_key", columns={"secret_key"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="Repository\Service")
 * @ORM\HasLifecycleCallbacks
 */
class Service extends \Entity\AbstractEntity
{
    const HASH_LEN = 32; 
    const SECRET_KEY_LEN = 16;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=false)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="secret_key", type="string", length=16, nullable=false)
     */
    private $secretKey;

    /**
     * @var \Zend_Date
     *
     * @ORM\Column(name="date_create", type="zenddate", nullable=false)
     */
    private $dateCreate;

    /**
     * @var integer
     *
     * @ORM\Column(name="webshots_per_day", type="integer", nullable=false)
     */
    private $webshotsPerDay = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="create_user_id", referencedColumnName="id")
     * })
     */
    private $createUser;
    
    /**
     * Lista screenshotow wykonanych dla aplikacji
     *
     * @ORM\OneToMany(targetEntity="Entity\Webshot", mappedBy="service", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"id" = "ASC"})
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $webshots;

    /**
     * Instancja konstruktora
     * 
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->webshots = new \Doctrine\Common\Collections\ArrayCollection();
        parent::__construct($data);
    }

    /** 
     * @ORM\PrePersist 
     */
    public function preInsert()
    {
        $this->dateCreate = new \Zend_Date();
        $this->hash = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'hash', self::HASH_LEN);
        $this->secretKey = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'secretKey', self::SECRET_KEY_LEN);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Service
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Service
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
     * Set secretKey
     *
     * @param string $secretKey
     * @return Service
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    
        return $this;
    }

    /**
     * Get secretKey
     *
     * @return string 
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set dateCreate
     *
     * @param \Zend_Date $dateCreate
     * @return Service
     */
    public function setDateCreate(\Zend_Date $dateCreate)
    {
        $this->dateCreate = $dateCreate;
    
        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \Zend_Date 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set webshotsPerDay
     *
     * @param integer $webshotsPerDay
     * @return Service
     */
    public function setWebshotsPerDay($webshotsPerDay)
    {
        $this->webshotsPerDay = (int) $webshotsPerDay;
    
        return $this;
    }

    /**
     * Get webshotsPerDay
     *
     * @return integer 
     */
    public function getWebshotsPerDay()
    {
        return $this->webshotsPerDay;
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
     * Set createUser
     *
     * @param \Entity\User $createUser
     * @return Service
     */
    public function setCreateUser(\Entity\User $createUser = null)
    {
        $this->createUser = $createUser;
    
        return $this;
    }

    /**
     * Get createUser
     *
     * @return \Entity\Users 
     */
    public function getCreateUser()
    {
        return $this->createUser;
    }
    
    /**
     * Metoda dodajaca webshot do aplikacji
     * 
     * @param \Entity\Webshot $webshot
     * @return \Entity\Service
     */
    public function addWebshot(\Entity\Webshot $webshot)
    {
        if (!$this->webshots->contains($webshot)) {
            $this->webshots->add($webshot);
            $webshot->setService($this);
        }
        
        return $this;
    }
    
    /**
     * Metoda usuwajaca webshot z aplikacji
     * 
     * @param \Entity\Webshot $webshot
     * @return \Entity\Service
     */
    public function removeWebshot(\Entity\Webshot $webshot)
    {
        if ($this->webshots->contains($webshot)) {
            $this->webshots->removeElement($webshot);
            $webshot->setService(null);
        }
        
        return $this;
    }
}