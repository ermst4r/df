<?php namespace App\Entity\Repository;
use App\Entity\Repository\Contract\iSpreadsheetHeader;
use App\Entity\SpreadsheetHeader;

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




class SpreadsheetHeaderRepository  implements iSpreadsheetHeader    {


    private $spreadsheet_header;

    /**
     * SpreadsheetHeaderRepository constructor.
     * @param SpreadsheetHeader $spreadsheet_header
     */
    public function __construct(SpreadsheetHeader $spreadsheet_header)
    {
        $this->spreadsheet_header =$spreadsheet_header;
    }

    /**
     * @param $fk_feed_id
     * @return mixed
     */
    public function removeSpreadsheetHeaders($fk_feed_id)
    {
        return $this->spreadsheet_header->where('fk_feed_id',$fk_feed_id)->delete();
    }

    public function removeSpreadsheetHeadersByChannel($fk_channel_feed_id,$fk_channel_type_id)
    {
        return $this->spreadsheet_header->where('fk_channel_feed_id',$fk_channel_feed_id)->
            where('fk_channel_type_id',$fk_channel_type_id)->delete();

    }

    /**
     * @param $data
     */
    public function saveSpreadsheetHeaders($data)
    {
        $this->spreadsheet_header->create($data);

    }

    public function pluckSpreadSheetHeaders($fk_channel_feed_id,$fk_channel_type_id)
    {
       return $this->spreadsheet_header->where('fk_channel_feed_id',$fk_channel_feed_id)->where('fk_channel_type_id',$fk_channel_type_id)->pluck('spreadsheet_header')->toArray();
    }


}