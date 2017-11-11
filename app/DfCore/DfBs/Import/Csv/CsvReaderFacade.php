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

/**
 * Class CsvMapping
 * @package App\DfCore\DfBs\Import\Csv
 */
class CsvReaderFacade extends CsvMappingFacade {



    public function __construct($file_name)
    {
        parent::__construct($file_name);

    }


    /**
     * Give us a row to fetch
     * @param $row
     * @return mixed
     */
    public function fetchRow($row)
    {
        $this->computeDelimiter();
        return $this->reader->fetchOne($row);
    }


    public function getAllCsvRows()
    {
        return $this->reader->fetch();

    }


}