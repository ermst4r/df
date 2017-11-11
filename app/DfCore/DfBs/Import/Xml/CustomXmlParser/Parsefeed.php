<?php
namespace App\DfCore\DfBs\Import\Xml\CustomXmlParser;
use App\DfCore\DfBs\Log\LoggerFacade;

/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */



class Parsefeed
{

    /**
     * @var
     */
    private $file_name;
    /**
     * @var
     */
    private $custom_parser;

    /**
     * @return mixed
     */
    public function getIdField()
    {
        return $this->id_field;
    }

    /**
     * @param mixed $id_field
     */
    public function setIdField($id_field)
    {
        $this->id_field = $id_field;
    }


    private $id_field;


    /**
     * Parsefeed constructor.
     * @param $file_name
     * @param $custom_parser
     */
    public function __construct($file_name, $custom_parser)
    {
        $this->file_name = $file_name;
        $this->custom_parser = $custom_parser;
    }


    /**
     * @return mixed
     */
    public function getCustomParser()
    {
        return $this->custom_parser;
    }

    /**
     * @param mixed $custom_parser
     */
    public function setCustomParser($custom_parser)
    {
        $this->custom_parser = $custom_parser;
    }



    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param mixed $file_name
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }


    public function writeNewFeedData()
    {
        $loadClass = 'App\DfCore\DfBs\Import\Xml\CustomXmlParser\\'.$this->custom_parser;
        if(class_exists($loadClass)) {
            $parser = new $loadClass;
            $xml_content = $parser->transformXmlFeed($this->file_name);
            $this->setIdField($parser->getIdField());
            file_put_contents($this->file_name,$xml_content);
            return true;
        } else {
            $msg = "Cannot load  custom parser {$loadClass}";
            LoggerFacade::addAlert($msg);
            return false;
        }

    }

}