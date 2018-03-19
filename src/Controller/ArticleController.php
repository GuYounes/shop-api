<?php

namespace App\Controller;

use App\EntityValidator\ArticleValidator;
use Doctrine\ORM\EntityManager;
use App\Entity\Article as Article;
use App\Entity\Categorie as Categorie;
use JMS\Serializer\SerializerBuilder as Serializer;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\ContentType\ContentType;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/articles/{id}", name="get_article", requirements={"id"="\d+"}, defaults={"_format": "json"}, methods={"GET"})
     */
    public function getArticleAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Article::class);

        $article = $repo->find(["id" => $id]);

        if(is_null($article)) {
            return new Response(json_encode(["error" => "Page not found"]), 404);
        }

        $serializer = Serializer::create()->build();
        $json = $serializer->serialize($article, 'json');
        return new Response($json);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/articles", name="get_articles", defaults={"_format": "json"}, methods={"GET"})
     */
    public function getArticlesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Article::class);

        $articles = $repo->findAll();

        $serializer = Serializer::create()->build();
        $json = $serializer->serialize($articles, 'json');
        return new Response($json);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/articles", name="create_article", requirements={"_format": "json|xml"}, defaults={"_format": "json"}, methods={"POST"})
     */
    public function createArticleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorieRepo = $em->getRepository(Categorie::class);

        $data = $request->getContent();
        $contentType = $request->getContentType();

        $serializer = Serializer::create()->build();
        $validator = ArticleValidator::getInstance();
        $article = null;

        try{
            switch ($contentType){
                case ContentType::JSON:
                    $article = $serializer->deserialize($data, "App\Entity\Article", 'json');
                    break;
                case ContentType::XML:
                    $article = $serializer->deserialize($data, "App\Entity\Article", 'xml');
                    break;
            };
        } catch (\Exception $e) {
            echo $e->getMessage();
            Return new Response(json_encode(["error" => "Parsing error, verify your document"]), 415);
        }

        if(is_null($article)){
            Return new Response(json_encode(["error" => "Your media is not supported", "media supported" => ["json", "xml" ]]), 415);
        }

        if (!$validator->validate($article)) {
            return new Response(json_encode($validator->getValidationErrors()), 400);
        }

        $categorie = $categorieRepo->find($article->getCategorie()->getId());
        $article->setCategorie($categorie);

        if(is_null($categorie)){
            return new Response(json_encode(["error" => "The article's category doesn't ex  isist"]), 400);
        }

        $em->persist($article);
        $em->flush();

        $jsonArticle = $serializer->serialize($article, 'json');
        return new Response($jsonArticle);
    }
}
