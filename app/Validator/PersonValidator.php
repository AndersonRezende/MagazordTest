<?php

namespace App\Validator;

class PersonValidator implements Validator
{
    public static function validate($request)
    {
        $errors = '';
        if(!isset($request['name']) || !isset($request['cpf'])) {
            $errors .= 'Os campos Nome e CPF devem estar presentes. ';
        } else {
            $name = $request['name'];
            $cpf = $request['cpf'];
            $nameLen = strlen($name);
            $cpfLen = strlen($cpf);
            if ($nameLen < 1 || $nameLen > 100) {
                $errors .= 'O tamanho do campo Nome não é válido. ';
            }
            if ($cpfLen != 11) {
                $errors .= 'O tamanho do campo CPF não é válido. ';
            }
            $errors .= is_numeric($cpf) ? '' : 'O campo CPF deve ser numérico. ';
        }
        return $errors;
    }
}