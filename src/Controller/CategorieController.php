<?php

namespace App\Controller;

use App\ContentType\ContentType;
use App\Entity\Categorie;
use App\EntityValidator\CategorieValidator;
use JMS\Serializer\SerializerBuilder as Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategorieController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/categories/{id}", name="get_categorie", requirements={"id"="\d+"}, defaults={"_format": "json"}, methods={"GET"})
     */
    public function getCategorieAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Categorie::class);

        $categorie = $repo->find(["id" => $id]);

        if(is_null($categorie)) {
            return new Response(json_encode(["error" => "The categorie with ID $id doesn't exists"]), 404);
        }

        $serializer = Serializer::create()->build();
        $json = $serializer->serialize($categorie, 'json');

        return new Response($json);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/categories", name="get_categories", defaults={"_format": "json"}, methods={"GET"})
     */
    public function getCategoriesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Categorie::class);

        $categories = $repo->findAll();

        $serializer = Serializer::create()->build();
        $json = $serializer->serialize($categories, 'json');

        return new Response($json);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/categories", name="create_categorie", defaults={"_format": "json"}, methods={"POST"})
     */
    public function createCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorieRepo = $em->getRepository(Categorie::class);

        $data = $request->getContent();
        $contentType = $request->getContentType();

        $serializer = Serializer::create()->build();
        $validator = CategorieValidator::getInstance();
        $categorie = null;

        try{
            switch ($contentType){
                case ContentType::JSON:
                    $categorie = $serializer->deserialize($data, "App\Entity\Categorie", 'json');
                    break;
                case ContentType::XML:
                    $categorie = $serializer->deserialize($data, "App\Entity\Categorie", 'xml');
                    break;
            };
        } catch (\Exception $e) {
            echo $e->getMessage();
            Return new Response(json_encode(["error" => "Parsing error, verify your document"]), 415);
        }

        if(is_null($categorie)){
            Return new Response(json_encode(["error" => "Your media is not supported", "media supported" => ["json", "xml" ]]), 415);
        }

        $categorie->setId(null);

        if (!$validator->validate($categorie)) {
            return new Response(json_encode($validator->getValidationErrors()), 400);
        }

        $em->persist($categorie);
        $em->flush();

        $json = $serializer->serialize($categorie, 'json');

        return new Response($json);
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return Response
     *
     * @Route("/categories/{id}", name="delete_categorie", requirements={"id"="\d+"}, defaults={"_format": "json"}, methods={"DELETE"})
     */
    public function deleteCategorieAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Categorie::class);

        $categorie = $repo->find(["id" => $id]);

        if(is_null($categorie)) {
            return new Response(json_encode(["error" => "The categorie with ID $id doesn't exists"]), 404);
        }

        $em->remove($categorie);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     *
     * @Route("/categories/{id}", name="update_categorie", requirements={"id"="\d+"}, defaults={"_format": "json"}, methods={"PUT"})
     */
    public function updateCategorieAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorieRepo = $em->getRepository(Categorie::class);

        $data = $request->getContent();
        $contentType = $request->getContentType();

        $serializer = Serializer::create()->build();
        $validator = CategorieValidator::getInstance();
        $newCategorie = null;

        $categorie = $categorieRepo->find(["id" => $id]);

        if(is_null($categorie)) {
            return new Response(json_encode(["error" => "The categorie with ID $id doesn't exists"]), 404);
        }

        try{
            switch ($contentType){
                case ContentType::JSON:
                    $newCategorie = $serializer->deserialize($data, "App\Entity\Categorie", 'json');
                    break;
                case ContentType::XML:
                    $newCategorie = $serializer->deserialize($data, "App\Entity\Categorie", 'xml');
                    break;
            };
        } catch (\Exception $e) {
            echo $e->getMessage();
            Return new Response(json_encode(["error" => "Parsing error, verify your document"]), 415);
        }

        if(is_null($newCategorie)){
            Return new Response(json_encode(["error" => "Your media is not supported", "media supported" => ["json", "xml" ]]), 415);
        }

        if (!$validator->validate($newCategorie)) {
            return new Response(json_encode($validator->getValidationErrors()), 400);
        }

        $categorie->replaceWith($newCategorie);
        $em->flush();

        $json = $serializer->serialize($categorie, 'json');

        return new Response($json);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     *
     * @Route("/categories/{id}", name="patch_categorie", requirements={"id"="\d+"}, defaults={"_format": "json"}, methods={"PATCH"})
     */
    public function patchCategorieAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorieRepo = $em->getRepository(Categorie::class);

        $data = $request->getContent();
        $contentType = $request->getContentType();

        $serializer = Serializer::create()->build();
        $newCategorie = null;

        $categorie = $categorieRepo->find(["id" => $id]);

        if(is_null($categorie)) {
            return new Response(json_encode(["error" => "The categorie with ID $id doesn't exists"]), 404);
        }

        try{
            switch ($contentType) {
                case ContentType::JSON:
                    $newCategorie = $serializer->deserialize($data, "App\Entity\Categorie", 'json');
                    break;
                case ContentType::XML:
                    $newCategorie = $serializer->deserialize($data, "App\Entity\Categorie", 'xml');
                    break;
            };
        } catch (\Exception $e) {
            echo $e->getMessage();
            Return new Response(json_encode(["error" => "Parsing error, verify your document"]), 415);
        }

        if (is_null($newCategorie)) {
            Return new Response(json_encode(["error" => "Your media is not supported", "media supported" => ["json", "xml" ]]), 415);
        }

        if ($libelle = $newCategorie->getLibelle()) $categorie->setLibelle($libelle);

        $em->flush();

        $json = $serializer->serialize($categorie, 'json');

        return new Response($json);
    }
}
