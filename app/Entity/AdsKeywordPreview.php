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

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class AdsKeywordPreview extends Model
{
    protected $table = 'ads_keyword_preview';
    protected $fillable = ['formatted_keyword','delete_keyword','adwords_id','fk_adwords_feed_id','fk_adwords_keyword_id','fk_adgroup_preview_id','parent','keyword_type'];
}