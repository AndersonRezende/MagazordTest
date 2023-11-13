<?php

namespace App\Validator;

class ContactValidator implements Validator
{
    public static function validate($request)
    {
        $errors = '';
        if(!isset($request['person']) || !isset($request['type']) || !isset($request['description'])) {
            $errors .= 'Os campos Pessoa, Tipo e descrição devem estar presentes. ';
        } else {
            $description = $request['description'];
            $person = $request['person'];
            $type = $request['type'];
            $descriptionLen = strlen($description);
            if ($descriptionLen < 1 || $descriptionLen > 100) {
                $errors .= 'O tamanho do campo Descrição não é válido. ';
            }
            if (!is_numeric($type) || ($type != 0 && $type != 1)) {
                $errors .= 'O campo Tipo é inválido. ';
            }
            $errors .= is_numeric($person) ? '' : 'O campo Pessoa deve ser numérico. ';
        }
        return $errors;
    }
}