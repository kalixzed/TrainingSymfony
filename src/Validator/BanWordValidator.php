<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BanWordValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var BanWord $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        // Conversion en minuscule pour la comparaison
        $value = strtolower($value);

        // Liste des mots à bannir
        foreach ($constraint->banWords as $banWord)
        {
            // Utilisation de la fonction str_contains pour vérifier si le mot à bannir est présent dans la chaîne de caractères
            if (str_contains($value, $banWord)){
            // Si le mot est trouvé, on ajoute une violation
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ banWord }}', $banWord)
                    ->addViolation();
            }
        }
    }
}
