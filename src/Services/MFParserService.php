<?php

namespace EvolutionCMS\MFParser\Services;

use EvolutionCMS\MFParser\Contracts\IParserContract;
use EvolutionCMS\Models\SiteTmplvarContentvalue;
use Exception;

class MFParserService
{
    /**
     * @var $parsers IParserContract[]
     */

    private array $parsers;

    /**
     * @throws Exception
     */
    public function __construct(array $parsers=[])
    {
        $this->setParsers($parsers);
    }

    /**
     * @throws Exception
     */
    public function setParsers(array $parsersClasses=[])
    {
        foreach($parsersClasses as $parser){
            if($parser instanceof IParserContract){
                $this->setParser($parser);
            }else{
                throw new Exception('Wrong parser type');
            }
        }
    }
    public function setParser(IParserContract $parser)
    {
        $this->parsers[$parser->getName()] = $parser;
    }

    /**
     * @throws Exception
     */
    public function parse(array $value, $parserName = 'default'):array
    {
        if(!empty($this->parsers[$parserName])){
            $result = $this->parsers[$parserName]->parse($value);
        }else{
            throw new Exception($parserName.' parser not found');
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function parseJson(string $value, $parserName = 'default'):array
    {
        $array = json_decode($value,true);

        if(json_last_error() === JSON_ERROR_NONE){
            return $this->parse($array,$parserName);
        }else{
            throw new Exception('Wrong string type, json expected');
        }
    }

    /**
     * @throws Exception
     */
    public function parseDoc(int $docId, int $tvId):array
    {
        $record = SiteTmplvarContentvalue::where('tmplvarid',$tvId)->where('contentid',$docId)->first();

        if(empty($record)){
            throw new Exception('TV='.$tvId.' on DOC='.$docId.' not found');
        }

        if(empty($record->value)){
            throw new Exception('TV='.$tvId.' on DOC='.$docId.' value is empty');
        }

        if(!is_string($record->value)){
            throw new Exception('TV='.$tvId.' on DOC='.$docId.' value must be string');
        }

        return $this->parseJson($record->value);
    }
}