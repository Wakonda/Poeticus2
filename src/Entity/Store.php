<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreRepository")
 */
class Store
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $text;

    /**
     * @ORM\Column(type="text")
     */
    protected $tag;
	
	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Biography")
     */
    protected $biography;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $amazonCode;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     */
	protected $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $photo;
	
	private $partnerId = "activiparano-21";
	
	public function getExternalStoreLink()
	{
		return "http://www.amazon.fr/dp/".$this->amazonCode."/ref=nosim?tag=".$this->partnerId;
	}

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

	public function getLanguage()
	{
		return $this->language;
	}
	
	public function setLanguage($language)
	{
		$this->language = $language;
	}

    /**
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
		$this->text = $text;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set biography
     *
     * @param string $biography
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * Get biography
     *
     * @return string 
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set amazonCode
     *
     * @param integer $amazonCode
     */
    public function setAmazonCode($amazonCode)
    {
        $this->amazonCode = $amazonCode;
    }

    /**
     * Get amazonCode
     *
     * @return integer 
     */
    public function getAmazonCode()
    {
        return $this->amazonCode;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }
}