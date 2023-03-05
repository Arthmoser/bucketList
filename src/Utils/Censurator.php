<?php

namespace App\Utils;

class Censurator
{

    const BAN_WORDS = ['Prout', 'con', 'Raphaël'];
    public function purify(string $sentence) {


    return str_ireplace(self::BAN_WORDS, '****', $sentence);

    }

}