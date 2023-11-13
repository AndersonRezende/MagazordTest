<?php

namespace App\Validator;

interface Validator
{
    public static function validate($request);
}