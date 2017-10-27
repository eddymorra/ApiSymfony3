<?php

// src/OC/PlatformBundle/Controller/ApiController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ApiController extends Controller
{
    // POST pour créer un article
    // GET pour retourner un JSON contenant tout les articles
    public function simpleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $leading = $request->request->get('leading_art');
            $body = $request->request->get('body');
            $slug = preg_replace('~[^\pL\d]+~u', '-', $title);
            $createdBy = $request->request->get('createdBy');

            $article = new Article();
                $article->setTitle($title);
                $article->setLeadingArt($leading);
                $article->setBody($body);
                $article->setSlug($slug);
                $article->setCreatedBy($createdBy);

            $em->persist($article);
            $em->flush();

            return new Response('OK');
        }
        else {
            $result = $em->getRepository('OCPlatformBundle:Article')->getArrayAllArticles();

            $response = new JsonResponse($result);

            return $response;
        }
    }

    // API par DELETE : Supprime un article désigné par son ID
    // API par GET : Retourne un JSON d'un article par son ID
    public function detailAction($id, Request $request)
    {
        if ($request->isMethod('DELETE')) {
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('OCPlatformBundle:Article')->getArticleById($id);
    
            $em->remove($article);
            $em->flush();
    
            return $this->redirectToRoute('oc_platform_list');
        }
        else {
            $repository = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Article');
            
            $article = $repository->getArticleById($id);

            $response = new JsonResponse(array(
                'id'            => $article->getId(),
                'title'         => $article->getTitle(),
                'leadingart'    => $article->getLeadingArt(),
                'body'          => $article->getBody(),
                'createdAt'     => $article->getCreatedAt(),
                'createdBy'     => $article->getCreatedBy()
            ));

            return $response;
        }
    }
    
}