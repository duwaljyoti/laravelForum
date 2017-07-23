<?php

namespace App\Inspections;

class InvalidKeyword
{
    public function detect($body)
    {
        $invalidKeyWords = [
            'spam Texts',
            'Bad words'
        ];

        foreach ($invalidKeyWords as $invalidKeyWord) {
            if(stripos($body, $invalidKeyWord) !== false) {
                throw new \Exception('Spam Detected');
            }
        }
    }
}