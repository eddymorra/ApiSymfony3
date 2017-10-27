<?php

// src/OC/PlatformBundle/Controller/ArticleController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use OC\PlatformBundle\Form\ArticleType;

class ArticleController extends Controller
{

    // Afficher tout les articles
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Article');
        
        $articles = $repository->getAllArticles();

        $content = $this->get('templating')
            ->render('OCPlatformBundle:Default:index.html.twig', array('articles' => $articles));

        return new Response($content);
    }

    // Afficher un article précis par son SLUG (id pour l'instant)
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Article');
        
        $article = $repository->getArticleById($id);

        return $this->render('OCPlatformBundle:Default:article.html.twig', array('article' => $article));
    }

    // Afficher un formulaire puis le traitement du retour de celui-ci (Pour ajout d'un article)
    public function addAction(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $slug = preg_replace('~[^\pL\d]+~u', '-', $article->getTitle());
            $article->setSlug($slug);
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('oc_platform_view', array('id' => $article->getId()));
        }
        else {
            return $this->render('OCPlatformBundle:Default:add.html.twig', array('form' => $form->createView()));
        }

    }

    // Méthode de suppression d'un article par son ID
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('OCPlatformBundle:Article')->getArticleById($id);

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('oc_platform_homepage');
    }
    
}