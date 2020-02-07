<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Entity\Language;
use App\Form\Type\CollectionType;
use App\Service\GenericFunction;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class CollectionAdminController extends AbstractController
{
	public function indexAction(Request $request)
	{
		return $this->render('Collection/index.html.twig');
	}

	public function indexDatatablesAction(Request $request, TranslatorInterface $translator)
	{
		$iDisplayStart = $request->query->get('iDisplayStart');
		$iDisplayLength = $request->query->get('iDisplayLength');
		$sSearch = $request->query->get('sSearch');

		$sortByColumn = array();
		$sortDirColumn = array();
			
		for($i=0 ; $i<intval($request->query->get('iSortingCols')); $i++)
		{
			if ($request->query->get('bSortable_'.intval($request->query->get('iSortCol_'.$i))) == "true" )
			{
				$sortByColumn[] = $request->query->get('iSortCol_'.$i);
				$sortDirColumn[] = $request->query->get('sSortDir_'.$i);
			}
		}

		$entityManager = $this->getDoctrine()->getManager();
		$entities = $entityManager->getRepository(Collection::class)->getDatatablesForIndex($iDisplayStart, $iDisplayLength, $sortByColumn, $sortDirColumn, $sSearch);
		$iTotal = $entityManager->getRepository(Collection::class)->getDatatablesForIndex($iDisplayStart, $iDisplayLength, $sortByColumn, $sortDirColumn, $sSearch, true);

		$output = array(
			"sEcho" => $request->query->get('sEcho'),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iTotal,
			"aaData" => array()
		);
		
		foreach($entities as $entity)
		{
			$row = array();
			$row[] = $entity->getId();
			$row[] = $entity->getTitle();
			$row[] = $entity->getLanguage()->getTitle();
			
			$show = $this->generateUrl('collectionadmin_show', array('id' => $entity->getId()));
			$edit = $this->generateUrl('collectionadmin_edit', array('id' => $entity->getId()));
			
			$row[] = '<a href="'.$show.'" alt="Show">'.$translator->trans('admin.index.Read').'</a> - <a href="'.$edit.'" alt="Edit">'.$translator->trans('admin.index.Update').'</a>';

			$output['aaData'][] = $row;
		}

		$response = new Response(json_encode($output));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

    public function newAction(Request $request)
    {
		$entity = new Collection();
		$entityManager = $this->getDoctrine()->getManager();
		$entity->setLanguage($entityManager->getRepository(Language::class)->findOneBy(["abbreviation" => $request->getLocale()]));
        $form = $this->genericCreateForm($request->getLocale(), $entity);

		return $this->render('Collection/new.html.twig', array('form' => $form->createView()));
    }
	
	public function createAction(Request $request, TranslatorInterface $translator)
	{
		$entity = new Collection();
        $form = $this->genericCreateForm($request->getLocale(), $entity);
		$form->handleRequest($request);
		
		$this->checkForDoubloon($translator, $entity, $form);

		if($form->isValid())
		{
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($entity);
			$entityManager->flush();

			$redirect = $this->generateUrl('collectionadmin_show', array('id' => $entity->getId()));

			return $this->redirect($redirect);
		}
		
		return $this->render('Collection/new.html.twig', array('form' => $form->createView()));
	}
	
	public function showAction(Request $request, $id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$entity = $entityManager->getRepository(Collection::class)->find($id);
	
		return $this->render('Collection/show.html.twig', array('entity' => $entity));
	}
	
	public function editAction(Request $request, $id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$entity = $entityManager->getRepository(Collection::class)->find($id);
		$form = $this->genericCreateForm($request->getLocale(), $entity);
	
		return $this->render('Collection/edit.html.twig', array('form' => $form->createView(), 'entity' => $entity));
	}

	public function updateAction(Request $request, TranslatorInterface $translator, $id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$entity = $entityManager->getRepository(Collection::class)->find($id);

		$form = $this->genericCreateForm($request->getLocale(), $entity);
		$form->handleRequest($request);
		
		$this->checkForDoubloon($translator, $entity, $form);
		
		if($form->isValid())
		{
			$entityManager->persist($entity);
			$entityManager->flush();

			$redirect = $this->generateUrl('collectionadmin_show', array('id' => $id));

			return $this->redirect($redirect);
		}
	
		return $this->render('Collection/edit.html.twig', array('form' => $form->createView(), 'entity' => $entity));
	}
	
	private function genericCreateForm($locale, $entity)
	{
		return $this->createForm(CollectionType::class, $entity, array("locale" => $locale));
	}

	private function checkForDoubloon(TranslatorInterface $translator, $entity, $form)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$checkForDoubloon = $entityManager->getRepository(Collection::class)->checkForDoubloon($entity);

		if($checkForDoubloon > 0)
			$form->get("title")->addError(new FormError($translator->trans("admin.index.ThisEntryAlreadyExists")));
	}
}