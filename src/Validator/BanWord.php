<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


// 
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{

    public function __construct(
        // public string $message permet de spécifier le message d'erreur par défaut
        public string $message = 'Ce mot est un mot banni "{{ banWord }}". Veuillez changer de mot.', 
        // public $banWords permet de définir la liste des mots bannis 
        public $banWords = ['spam', 'viagra', 'pénis'],
        // public array|null $groups permet de spécifier les groupes à valider pour cette contrainte  (ici, aucun groupe spécifié)
        ?array $groups = null,
        // public mixed $payload permet de passer des données supplémentaires à la validation (ici, le mot banni)
        mixed $payload = null)
    {
        // Appel au constructeur parent qui prend en compte le message et la liste des mots bannis (__construct)
        // La méthode parent::__construct(null, $groups, $payload) appelle le constructeur parent avec des paramètres nuls
        // Les paramètres $groups et $payload sont facultatifs et peuvent être utilisés pour spécifier des groupes et des données supplémentaires pour la validation
        parent::__construct(null, $groups, $payload);
    }

    
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
}
