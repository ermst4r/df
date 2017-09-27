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

namespace App\DfCore\DfBs\Import\Category\CategoryChannels;
use App\DfCore\DfBs\Import\Category\CategoryChannels\Contract\iChannel;
use App\DfCore\DfBs\Import\Remote\RemoteFileService;
use Carbon\Carbon;
use League\Csv\Reader;


/**
 * Let us instantiate a specific file
 * Class CsvMapping
 * @package App\DfCore\DfBs\Import\Csv
 */
class GoogleShopping implements iChannel {

    protected $file_name = '/google_shopping/nl_NL.csv';

    /**
     * @param $raw
     * @return array
     */
   public function parseChannelData()
   {
       $file = RemoteFileService::getCategoryStorageFolder().$this->file_name;
       $reader = Reader::createFromPath($file);
       $reader->setDelimiter("-");
       $results = [];
       foreach($reader->fetch() as $row) {
           $results[] = [
               'category_name'=>$row[1],
               'type'=>'google_shopping',
               'created_at'=>Carbon::now()->tz(DFBULDER_TIMEZONE),
               'updated_at'=>Carbon::now()->tz(DFBULDER_TIMEZONE),
               'category_meta'=> json_encode(
                   [
                        'id'=>$row[0]
                    ]
               )
           ];
       }

       return $results;
   }


}