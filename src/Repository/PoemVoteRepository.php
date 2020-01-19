<?php

namespace App\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\PoemVote;

/**
 * PoemVote repository
 */
class PoemVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoemVote::class);
    }
	
	public function checkIfUserAlreadyVote($idPoem, $idUser)
	{
		$qb = $this->createQueryBuilder("vo");
		
		$qb->select("COUNT(vo)")
		   ->leftjoin("vo.user", "bp")
		   ->leftjoin("vo.poem", "pf")
		   ->where("bp.id = :bpId")
		   ->andWhere("pf.id = :pfId")
		   ->setParameter("bpId", $idUser)
		   ->setParameter("pfId", $idPoem);

		return $qb->getQuery()->getSingleScalarResult();
	}

	public function countVoteByPoem($idPoem, $vote)
	{
		$qb = $this->createQueryBuilder("vo");
		
		$qb->select("COUNT(vo)")
		   ->leftjoin("vo.poem", "pf")
		   ->where("vo.vote = :vote")
		   ->andWhere("pf.id = :pfId")
		   ->setParameter("vote", $vote)
		   ->setParameter("pfId", $idPoem);

		return $qb->getQuery()->getSingleScalarResult();
	}

	public function findVoteByUser($iDisplayStart, $iDisplayLength, $sortByColumn, $sortDirColumn, $sSearch, $username, $count = false)
	{
		$qb = $this->createQueryBuilder("vo");

		$aColumns = array('pf.title', 'vo.vote');
		
		$qb->select("pf.id, pf.title, pf.slug, vo.vote")
		   ->leftjoin("vo.user", "bp")
		   ->leftjoin("vo.poem", "pf")
		   ->where("bp.username = :username")
		   ->setParameter("username", $username);
		   
		if(!empty($sortDirColumn))
		   $qb->orderBy($aColumns[$sortByColumn[0]], $sortDirColumn[0]);
		
		if(!empty($sSearch))
		{
			$search = "%".$sSearch."%";
			$qb->andhere('pf.title LIKE :search')
			   ->setParameter('search', $search);
		}
		if($count)
		{
			$qb->select("COUNT(vo) AS count");
			return $qb->getQuery()->getSingleScalarResult();
		}
		else
			$qb->setFirstResult($iDisplayStart)->setMaxResults($iDisplayLength);

		return $qb->getQuery()->getResult();
	}
}