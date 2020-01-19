<?php

namespace App\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Page;

/**
 * Page repository
 */
class PageRepository extends ServiceEntityRepository implements iRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findByName($name, $locale)
    {
		$qb = $this->createQueryBuilder("pa");

		$qb->where('pa.internationalName = :internationalName')
		   ->setParameter('internationalName', $name);
		
		$this->whereLanguage($qb, 'pa', $locale);
		$data = $qb->execute()->fetch();

        return $this->getQuery()->getResult();
    }
	
	public function getDatatablesForIndex($iDisplayStart, $iDisplayLength, $sortByColumn, $sortDirColumn, $sSearch, $count = false)
	{
		$qb = $this->createQueryBuilder("pa");

		$aColumns = array( 'pa.id', 'pa.title', 'pa.id');
		
		$qb->leftjoin("pa.language", "la");
		
		if(!empty($sortDirColumn))
		   $qb->orderBy($aColumns[$sortByColumn[0]], $sortDirColumn[0]);
		
		if(!empty($sSearch))
		{
			$search = "%".$sSearch."%";
			$qb->where('pa.title LIKE :search')
			   ->setParameter('search', $search);
		}
		if($count)
		{
			$qb->select("COUNT(pa) AS count");
			return $qb->getQuery()->getSingleScalarResult();
		}
		else
			$qb->setFirstResult($iDisplayStart)->setMaxResults($iDisplayLength);

		return $qb->getQuery()->getResult();
	}
	
	public function checkForDoubloon($entity)
	{
		$qb = $this->createQueryBuilder("pa");

		$qb->select("COUNT(pa) AS number")
		   ->leftjoin("pa.language", "la")
		   ->where("pa.title = :title")
		   ->setParameter('title', $entity->getTitle())
		   ->andWhere("la.id = :idLanguage")
		   ->setParameter("idLanguage", $entity->getLanguage());

		if($entity->getId() != null)
		{
			$qb->andWhere("pa.id != :id")
			   ->setParameter("id", $entity->getId());
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
}