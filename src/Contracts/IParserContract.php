<?php

namespace EvolutionCMS\MFParser\Contracts;

interface IParserContract
{
    public function getName():string;
    public function parse(array $value):array;
}