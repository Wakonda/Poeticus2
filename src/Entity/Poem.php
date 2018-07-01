<?php

namespace App\Entity;

use App\Service\GenericFunction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PoemRepository")
 */
class Poem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $releasedDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $authorType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;
	
	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\PoeticForm")
     */
    protected $poeticForm;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Biography")
     */
    protected $biography;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     */
    protected $country;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collection")
     */
    protected $collection;
	
	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    protected $user;

    /**
     * @ORM\Column(type="integer")
     */
    protected $state;
	
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $photo;
	
	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     */
	protected $language;

	public function getStateString()
	{
		$res = "";
		
		switch($this->state)
		{
			case 0:
				$res = "poem.state.Published";
				break;
			case 1:
				$res = "poem.state.Draft";
				break;
			case 2:
				$res = "poem.state.Deleted";
				break;
			default:
				$res = "";
		}
		
		return $res;
	}

	public function getStateRealName()
	{
		$res = "";
		
		switch($this->state)
		{
			case 0:
				$res = "published";
				break;
			case 1:
				$res = "draft";
				break;
			case 2:
				$res = "deleted";
				break;
			default:
				$res = "";
		}
		
		return $res;
	}
	
	public function isBiography()
	{
		return $this->authorType == "biography";
	}

	public function isUser()
	{
		return $this->authorType == "user";
	}
	
	public function getAuthor()
	{
		if($this->isBiography())
			return $this->biography;
		else
			return $this->user;
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
		$this->setSlug();
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug()
    {
		if(empty($this->slug))
			$this->slug = GenericFunction::slugify($this->title);
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getReleasedDate()
    {
        return $this->releasedDate;
    }

    public function setReleasedDate($releasedDate)
    {
        $this->releasedDate = $releasedDate;
    }

    public function getAuthorType()
    {
        return $this->authorType;
    }

    public function setAuthorType($authorType)
    {
        $this->authorType = $authorType;
    }

    public function getBiography()
    {
        return $this->biography;
    }

    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPoeticForm()
    {
        return $this->poeticForm;
    }

    public function setPoeticForm($poeticForm)
    {
        $this->poeticForm = $poeticForm;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

	public function getLanguage()
	{
		return $this->language;
	}
	
	public function setLanguage($language)
	{
		$this->language = $language;
	}
}