<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Webshot
 *
 * @ORM\Table(name="webshots", 
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UNIQUE_hash", columns={"hash"})
 *  }, 
 *  indexes={
 *      @ORM\Index(name="fk_webshots_files_idx", columns={"image_id"}), 
 *      @ORM\Index(name="fk_webshots_service_idx", columns={"service_id"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="Repository\Webshots")
 * @ORM\HasLifecycleCallbacks
 */
class Webshot extends \Entity\AbstractEntity
{
    const HASH_LEN = 32; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=1024, nullable=false)
     */
    private $url;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url_md5", type="string", length=32, nullable=false)
     */
    private $urlMd5;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=false)
     */
    private $hash;

    /**
     * @var \Zend_Date
     *
     * @ORM\Column(name="date_create", type="zenddate", nullable=false)
     */
    private $dateCreate;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="content_md5", type="string", length=32, nullable=true)
     */
    private $contentMd5;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Entity\Service
     *
     * @ORM\ManyToOne(targetEntity="Entity\Service", inversedBy="webshots")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $service;

    /**
     * @var \Entity\File
     *
     * @ORM\ManyToOne(targetEntity="Entity\File")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $image;
    
    /** 
     * @ORM\PrePersist 
     */
    public function preInsert()
    {
        $this->dateCreate = new \Zend_Date();
        $this->hash = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'hash', self::HASH_LEN);
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Webshot
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->setUrlMd5(md5($url));

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Webshot
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Webshot
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Webshot
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Webshot
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Webshot
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set contentMd5
     *
     * @param string $contentMd5
     * @return Webshot
     */
    public function setContentMd5($contentMd5)
    {
        $this->contentMd5 = $contentMd5;

        return $this;
    }

    /**
     * Get contentMd5
     *
     * @return string 
     */
    public function getContentMd5()
    {
        return $this->contentMd5;
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
     * Set service
     *
     * @param \Entity\Service $service
     * @return Webshot
     */
    public function setService(\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set image
     *
     * @param \Entity\File $image
     * @return Webshot
     */
    public function setImage(\Entity\File $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Entity\File 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set urlMd5
     *
     * @param string $urlMd5
     * @return Webshot
     */
    public function setUrlMd5($urlMd5)
    {
        $this->urlMd5 = $urlMd5;

        return $this;
    }

    /**
     * Get urlMd5
     *
     * @return string 
     */
    public function getUrlMd5()
    {
        return $this->urlMd5;
    }
}