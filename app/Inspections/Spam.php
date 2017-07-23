<?php

namespace App\Inspections;

Class Spam
{
    protected $toInspect = [];

    public function detect($body)
    {
        $this->toInspect = [
            InvalidKeyword::class,
            RepeatedCharacters::class
        ];

        foreach($this->toInspect as $toInspect) {
            app($toInspect)->detect($body);
        }

        return false;
    }
}