<?php

namespace App\Controller;


use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Poem;
use App\Form\Type\CommentType;

class CommentController extends AbstractController
{
    public function indexAction(Request $request, $poemId)
    {
		$entity = new Comment();
        $form = $this->createForm(CommentType::class, $entity);

        return $this->render('Comment/index.html.twig', array('poemId' => $poemId, 'form' => $form->createView()));
    }
	
	public function createAction(Request $request, TokenStorageInterface $tokenStorage, TranslatorInterface $translator, $poemId)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$entity = new Comment();
        $form = $this->createForm(CommentType::class, $entity);
		$form->handleRequest($request);

		$user = $tokenStorage->getToken()->getUser();
		
		if(!empty($user) and is_object($user))
			$user = $entityManager->getRepository(User::class)->findByUsernameOrEmail($user->getUsername());
		else
		{
			$form->get("text")->addError(new FormError($translator->trans("comment.field.YouMustBeLoggedInToWriteAComment")));
		}

		if($form->isValid())
		{
			$entity->setUser($user);
			$entity->setPoem($entityManager->getRepository(Poem::class)->find($poemId));
			
			$entityManager->persist($entity);
			$entityManager->flush();
			
			$entities = $entityManager->getRepository(Comment::class)->findAll();
			$form = $this->createForm(CommentType::class, new Comment());
		}

		$params = $this->getParametersComment($request, $poemId);

		return $this->render('Comment/form.html.twig', array("form" => $form->createView(), "poemId" => $poemId));
	}
	
	public function loadCommentAction(Request $request, $poemId)
	{
		return $this->render('Comment/list.html.twig', $this->getParametersComment($request, $poemId));
	}
	
	private function getParametersComment($request, $poemId)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$max_comment_by_page = 7;
		$page = $request->query->get("page");
		$totalComments = $entityManager->getRepository(Comment::class)->countAllComments($poemId);
		$number_pages = ceil($totalComments / $max_comment_by_page);
		$first_message_to_display = ($page - 1) * $max_comment_by_page;
		
		$entities = $entityManager->getRepository(Comment::class)->displayComments($poemId, $max_comment_by_page, $first_message_to_display);

		return array("entities" => $entities, "page" => $page, "number_pages" => $number_pages);
	}
}
