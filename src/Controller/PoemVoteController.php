<?php

namespace App\Controller;

use App\Entity\PoemVote;
use App\Entity\Poem;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PoemVoteController extends AbstractController
{
	public function voteAction(Request $request, TranslatorInterface $translator, TokenStorageInterface $tokenStorage, $idPoem)
	{
		$vote = $request->query->get('vote');
		$entityManager = $this->getDoctrine()->getManager();
		
		$state = "";
		
		if(!empty($vote))
		{
			$user = $tokenStorage->getToken()->getUser();
			
			if(is_object($user))
			{
				$vote = ($vote == "up") ? 1 : -1;

				$entity = new PoemVote();
				
				$entity->setVote($vote);
				$entity->setPoem($entityManager->getRepository(Poem::class)->find($idPoem));

				$userDb = $entityManager->getRepository(User::class)->findByUsernameOrEmail($user->getUsername());
				$entity->setUser($userDb);
			
				$numberOfDoubloons = $entityManager->getRepository(PoemVote::class)->checkIfUserAlreadyVote($idPoem, $userDb->getId());
				
				if($numberOfDoubloons >= 1)
					$state = $translator->trans("vote.field.YouHaveAlreadyVotedForThis");
				else
				{
					$entityManager->persist($entity);
					$entityManager->flush();
				}
			}
			else
				$state = $translator->trans("vote.field.YouMustBeLoggedInToVote");
		}

		$up_values = $entityManager->getRepository(PoemVote::class)->countVoteByPoem($idPoem, 1);
		$down_values = $entityManager->getRepository(PoemVote::class)->countVoteByPoem($idPoem, -1);
		$total = $up_values + $down_values;
		$value = ($total == 0) ? 50 : round(((100 * $up_values) / $total), 1);

		$response = new Response(json_encode(array("up" => $up_values, "down" => $down_values, "value" => $value, "alreadyVoted" => $state)));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}