<?php

namespace App;

class Util
{
    static public function validatorErrorsToArray($errs)
    {
        $errors = [];

        foreach ($errs as $err) {
            foreach ($err as $e ) {
                $errors[] = $e;
            }
        }

        return $errors;
    }
}
