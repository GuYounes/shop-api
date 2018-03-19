<?php

namespace App\EntityValidator;

use App\Entity\Article;

class ArticleValidator{

    protected static $INSTANCE = null;
    protected $errors;

    protected function __construct(){}

    public static function getInstance() : ArticleValidator{
        if(is_null(self::$INSTANCE)){
            self::$INSTANCE = new ArticleValidator();
        }

        return self::$INSTANCE;
    }

    public function validate(Article $article) : bool {
        $this->errors = ["error" => []];

        if (!is_null($article->getId())) {
            $this->errors["error"]["id"][] = "id has to be null";
        }

        if (is_null($article->getLibelle()) || empty(trim($article->getLibelle()))) {
            $this->errors["error"]["libelle"][] = "libelle is null or empty";
        }

        if (is_null($article->getPrix())) {
            $this->errors["error"]["price"][] = "price is null";
        }

        if ($article->getPrix() < 0) {
            $this->errors["error"]["price"][] = "price is negative";
        }

        if (is_null($article->getCategorie())) {
            $this->errors["error"]["category"][] = "category is null";
        }

        if (sizeof($this->errors["error"]) != 0) {
            return false;
        }

        return true;
    }

    public function getValidationErrors() : array {
        return $this->errors;
    }
}