<?php
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

namespace App\DfCore\DfBs\Import\Csv;
use \League\Csv\Reader;

/**
 * Class CsvMapping
 * @package App\DfCore\DfBs\Import\Csv
 */
class CsvMappingFacade  {

    /** @var static  */
    protected $reader ;
    /** @var array  */
    protected $csv_seperators  = [',',';',"\t",'|'];


    /**
     * CsvMapping constructor.
     * @param $file_name
     */
    public function __construct($file_name)
    {
        $this->reader = Reader::createFromPath($file_name);
    }

    /**
     * @return array
     */
    public function getCsvSeperators()
    {
        return $this->csv_seperators;
    }



    /**
     * Compute the delimiter
     * @return array
     */
    public function computeDelimiter()
    {
        $seperator  = $this->reader->fetchDelimitersOccurrence($this->csv_seperators);
        $this->reader->setDelimiter(key($seperator));
        return $seperator;
    }

    /**
     * Let us show the csv headers.
     * @return array
     */
    public function showCsvMapping()
    {
        $this->computeDelimiter();
        $results = $this->reader->fetchOne();
        return $results;


    }











}