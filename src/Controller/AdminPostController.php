<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostCreateType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class AdminPostController extends AbstractController {

	/**
	* @Route("/admin/post", name="admin_post")
	*/
	public function index() 
	{
		return new Response('admin post');
	}

	/**
	* @Route("/admin/post/new", name="admin_post_new")
	*/
	public function new(Request $request,EntityManagerInterface $em) 
	{
		$post = new Post();
		$form = $this->createForm(PostCreateType::class,$post);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$post = $form->getData();
			$em->persist($post);
			$em->flush();
			return $this->redirectToRoute('admin_post');
		}
		return $this->renderForm('admin/post/new.html.twig', [
            'form' => $form,
        ]);
	}

}