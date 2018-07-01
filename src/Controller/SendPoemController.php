<?php

namespace App\Controller;


use App\Entity\Contact;
use App\Entity\Poem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\SendPoemType;

class SendPoemController extends Controller
{
    public function indexAction(Request $request, $poemId)
    {
		$form = $this->createForm(SendPoemType::class, null);

        return $this->render('Index/send_poem.html.twig', array('form' => $form->createView(), 'poemId' => $poemId));
    }
	
	public function sendAction(Request $request, \Swift_Mailer $mailer, $poemId)
	{
		$sendPoemForm = new SendPoemType();
		
		parse_str($request->request->get('form'), $form_array);

        $form = $this->createForm(SendPoemType::class, $form_array);
		
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$data = (object)($request->request->get($form->getName()));
			$entityManager = $this->getDoctrine()->getManager();
			$entity = $entityManager->getRepository(Poem::class)->find($poemId);
			
			$content = $this->renderView('Index/send_poem_message_content.html.twig', array(
				"data" => $data,
				"entity" => $entity
			));

			$mailer->getTransport()->setStreamOptions(["ssl" => ["verify_peer" => false, "verify_peer_name" => false]]);
			$message = (new \Swift_Message($data->subject))
				->setFrom('amatukami66@gmail.com', "Poéticus")
				->setTo($data->recipientMail)
				->setBody($content, 'text/html');
		
			$mailer->send($message);
			
			$response = new Response(json_encode(array("result" => "ok")));
			$response->headers->set('Content-Type', 'application/json');

			return $response;
		}

		$res = array("result" => "error");
		
		$res["content"] = $this->render('Index/send_poem_form.html.twig', array('form' => $form->createView(), 'poemId' => $poemId));
		
		$response = new Response(json_encode($res));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
}