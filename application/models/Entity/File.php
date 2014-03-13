<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table(name="files", uniqueConstraints={@ORM\UniqueConstraint(name="hash_UNIQUE", columns={"hash"})})
 * @ORM\Entity(repositoryClass="Repository\Files")
 * @ORM\HasLifecycleCallbacks
 */
class File extends \Entity\AbstractEntity
{
    const HASH_LEN = 32;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=false)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=1024, nullable=false)
     */
    private $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="file_md5", type="string", length=32, nullable=false)
     */
    private $fileMd5;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=255, nullable=false)
     */
    private $mimeType;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer", nullable=false)
     */
    private $size;

    /**
     * @var \Zend_Date
     *
     * @ORM\Column(name="date_create", type="zenddate", nullable=false)
     */
    private $dateCreate;

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
        if ($this->fileExists()) {
            $this->setSize(filesize($this->getFullFilePath()));
            $this->setFileMd5(md5_file($this->getFullFilePath()));
            $this->setMimeType(finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->getFullFilePath()));
        }

        $this->dateCreate = new \Zend_Date();
        $this->hash = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'hash', self::HASH_LEN);
    }
    
    /**
     * Set hash
     *
     * @param string $hash
     * @return File
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
     * Set filePath
     *
     * @param string $filePath
     * @return File
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string 
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
    
    /**
     * Metoda zwracajaca pelna sciezke do pliku
     * 
     * @return string
     */
    public function getFullFilePath()
    {
        return rtrim($this->getConfig()->system->storage, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($this->filePath, DIRECTORY_SEPARATOR);
    }
    
    /**
     * Metoda sprawdzajaca czy plik istnieje
     * 
     * @return boolean
     */
    public function fileExists()
    {
        return file_exists($this->getFullFilePath());
    }

    /**
     * Set fileMd5
     *
     * @param string $fileMd5
     * @return File
     */
    public function setFileMd5($fileMd5)
    {
        $this->fileMd5 = $fileMd5;

        return $this;
    }

    /**
     * Get fileMd5
     *
     * @return string 
     */
    public function getFileMd5()
    {
        return $this->fileMd5;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set dateCreate
     *
     * @param \Zend_Date $dateCreate
     * @return File
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getUrl()
    {
        return \Extlib\System::getInstance()->getDomain()->getAddress() . '/system/image/hash/' . $this->getHash();
    }
}