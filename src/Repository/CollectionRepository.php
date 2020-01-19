<?php

namespace App\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Collection;

/**
 * Collection repository
 */
class CollectionRepository extends ServiceEntityRepository implements iRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collection::class);
    }
	
	public function getDatatablesForIndex($iDisplayStart, $iDisplayLength, $sortByColumn, $sortDirColumn, $sSearch, $count = false)
	{
		$qb = $this->createQueryBuilder("pf");

		$aColumns = array('pf.id', 'pf.title', 'la.title', 'pf.id');
		
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
		$qb = $this->createQueryBuilder("co");
		
		$qb->leftjoin("co.language", "la")
		   ->where('la.abbreviation = :locale')
		   ->setParameter('locale', $locale)
		   ->orderBy("co.title", "ASC");

		return $qb;
	}
	
	public function findAllByAuthor($authorId)
    {
		$qb = $this->createQueryBuilder("co");
		
		$qb->leftjoin("co.biography", "bo")
		   ->where("bo.id = :biographyId")
		   ->setParameter("biographyId", $authorId)
		   ->orderBy("co.title", "ASC");

		return $qb->getQuery()->getResult();
    }

	public function checkForDoubloon($entity)
	{
		if($entity->getTitle() == null or $entity->getBiography() == null)
			return 0;

		$qb = $this->createQueryBuilder("pf");

		$qb->select("COUNT(pf) AS number")
		   ->leftjoin("pf.language", "la")
		   ->leftjoin("pf.biography", "bo")
		   ->where("pf.slug = :slug")
		   ->setParameter('slug', $entity->getSlug())
		   ->andWhere("bo.id = :biographyId")
		   ->setParameter("biographyId", $entity->getBiography())
		   ->andWhere("la.id = :idLanguage")
		   ->setParameter("idLanguage", $entity->getLanguage());

		if($entity->getId() != null)
		{
			$qb->andWhere("pf.id != :id")
			   ->setParameter("id", $entity->getId());
		}

		return $qb->getQuery()->getSingleScalarResult();
	}
	
	public function getAllPoemsByCollectionAndAuthorForPdf($id)
	{
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select("pf.title, pf.text, pf.releasedDate")
		   ->from("App\Entity\Poem", "pf")
		   ->leftjoin('pf.collection', 'co')
		   ->where("co.id = :collectionId")
		   ->setParameter('collectionId', $id);
		   
		return $qb->getQuery()->getResult();
	}
}