<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PoemVoteRepository")
 */
class PoemVote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $vote;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Poem")
     */
    protected $poem;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    protected $user;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getVote()
    {
        return $this->vote;
    }

    public function setVote($vote)
    {
        $this->vote = $vote;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPoem()
    {
        return $this->poem;
    }

    public function setPoem($poem)
    {
        $this->poem = $poem;
    }
}
