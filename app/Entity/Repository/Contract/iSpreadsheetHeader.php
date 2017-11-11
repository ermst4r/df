<?php
namespace App\Entity\Repository\Contract;


interface iSpreadsheetHeader {

    public function saveSpreadsheetHeaders($data);
    public function removeSpreadsheetHeaders($fk_feed_id);
    public function pluckSpreadSheetHeaders($fk_channel_feed_id,$fk_channel_type_id);
    public function removeSpreadsheetHeadersByChannel($fk_channel_feed_id,$fk_channel_type_id);



}