<?php

namespace EvolutionCMS\MFParser\Parsers;

use EvolutionCMS\MFParser\Contracts\IParserContract;

class Parser implements IParserContract
{
    protected string $name = 'default';

    public function getName():string
    {
        return $this->name;
    }

    public function parse(array $value):array
    {
        if(!empty($value)){
            if(!empty($value['value'])){
                return $value['value'];
            }

            if(!empty($value['items'])){
                return $this->parse($value['items']);
            }
        }

        return [];
    }
}