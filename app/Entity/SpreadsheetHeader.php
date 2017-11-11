<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class SpreadsheetHeader extends Model
{
    protected $table = 'spreadsheet_header_channel';
    protected $fillable = ['spreadsheet_header','fk_feed_id','fk_channel_feed_id','fk_channel_type_id'];

}
