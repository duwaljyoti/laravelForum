<?php

namespace App\Inspections;

class RepeatedCharacters
{
    public function detect($body)
    {
        // confusing regarding the use of preg_match

        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Spam Detected');
        }
    }
}