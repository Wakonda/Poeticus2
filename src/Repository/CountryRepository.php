<?php

namespace App\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Country;

/**
 * Country repository
 */
class CountryRepository extends ServiceEntityRepository implements iRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Country::class);
    }
	
	public function getDatatablesForIndex($iDisplayStart, $iDisplayLength, $sortByColumn, $sortDirColumn, $sSearch, $count = false)
	{
		$qb = $this->createQueryBuilder("pf");

		$aColumns = array( 'pf.id', 'pf.title', 'la.title', 'pf.id');
		
		$qb->leftjoin("pf.language", "la");
		
		if(!empty($sortDirColumn))
		   $qb->orderBy($aColumns[$sortByColumn[0]], $sortDirColumn[0]);
		
		if(!empty($sSearch))
		{
			$search = "%".$sSearch."%";
			$qb->where('pf.title LIKE :search')
			   ->setParameter('search', $search);
		}
		if($count)
		{
			$qb->select("COUNT(pf) AS count");
			return $qb->getQuery()->getSingleScalarResult();
		}
		else
			$qb->setFirstResult($iDisplayStart)->setMaxResults($iDisplayLength);

		return $qb->getQuery()->getResult();
	}

	public function findAllForChoice($locale)
	{
		$qb = $this->createQueryBuilder("cy");
		
		$qb->leftjoin("cy.language", "la")
		   ->where('la.abbreviation = :locale')
		   ->setParameter('locale', $locale)
		   ->orderBy("cy.title", "ASC");

		return $qb;
	}
	
	public function findAllByLanguage($locale)
	{
		$qb = $this->createQueryBuilder("co");

		$qb->leftjoin("co.language", "la")
		   ->where("la.abbreviation = :locale")
		   ->setParameter("locale", $locale);

		return $qb->getQuery()->getResult();
	}

	public function checkForDoubloon($entity)
	{
		$qb = $this->createQueryBuilder("co");

		$qb->select("COUNT(co) AS number")
		   ->leftjoin("co", "language", "la", "co.language_id = la.id")
		   ->where("co.slug = :slug")
		   ->setParameter('slug', $entity->getSlug())
		   ->andWhere("la.id = :id")
		   ->setParameter("id", $entity->getLanguage()->getId())
		   ;

		if($entity->getId() != null)
		{
			$qb->andWhere("co.id != :id")
			   ->setParameter("id", $entity->getId());
		}

		return $qb->getQuery()->getSingleScalarResult();
	}
}