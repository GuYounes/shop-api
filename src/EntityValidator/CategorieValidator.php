<?php

namespace App\EntityValidator;

use App\Entity\Categorie;

class CategorieValidator{

    protected static $INSTANCE = null;
    protected $errors;

    protected function __construct(){}

    public static function getInstance() : CategorieValidator{
        if(is_null(self::$INSTANCE)){
            self::$INSTANCE = new CategorieValidator();
        }

        return self::$INSTANCE;
    }

    public function validate(Categorie $categorie) : bool {
        $this->errors = ["error" => []];

        if (is_null($categorie->getLibelle()) || empty(trim($categorie->getLibelle()))) {
            $this->errors["error"]["libelle"][] = "libelle is null or empty";
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