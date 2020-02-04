<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LicenceRepository")
 */
class Licence
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var text $text
     *
     * @ORM\Column(type="text")
     */
    private $text;

    /**
	 * @Assert\File(maxSize="6000000")
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     */
    private $language;

	/**
     * @var string $internationalName
     *
     * @ORM\Column(type="string", length=255)
     */
    private $internationalName;

    /**
     * @var text $link
     *
     * @ORM\Column(type="string", length=255)
     */
    private $link;

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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * Set logo
     *
     * @param text $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * Get logo
     *
     * @return text 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return $this->getTmpUploadRootDir();
    }

	public function getAssetImagePath()
	{
		return "extended/photo/licence/";
	}

    protected function getTmpUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/'.$this->getAssetImagePath();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadLogo() {
        // the file property can be empty if the field is not required
        if (null === $this->logo) {
            return;
        }

		if(is_object($this->logo))
		{
			$NameFile = basename($this->logo->getClientOriginalName());
			$reverseNF = strrev($NameFile);
			$explodeNF = explode(".", $reverseNF, 2);
			$NNFile = strrev($explodeNF[1]);
			$ExtFile = strrev($explodeNF[0]);
			$NewNameFile = uniqid().'-'.$NNFile.".".$ExtFile;
			if(!$this->id){
				$this->logo->move($this->getTmpUploadRootDir(), $NewNameFile);
			}else{
				if (is_object($this->logo))
					$this->logo->move($this->getUploadRootDir(), $NewNameFile);
			}
			if (is_object($this->logo))
				$this->setLogo($NewNameFile);
		}
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage(\App\Entity\Language $language)
    {
        $this->language = $language;
    }

	/**
     * Set internationalName
     *
     * @param string $internationalName
     */
    public function setInternationalName($internationalName)
    {
        $this->internationalName = $internationalName;
    }

    /**
     * Get internationalName
     *
     * @return string 
     */
    public function getInternationalName()
    {
        return $this->internationalName;
    }

    /**
     * Set link
     *
     * @param text $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return text 
     */
    public function getLink()
    {
        return $this->link;
    }
}