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

class AdsPreview extends Model
{
    protected $table = 'ads_preview';
    protected $fillable = ['headline_1','headline_2','description','path_1','path_2','final_url','adwords_api_message'
        ,'fk_adwords_feed_id','fk_adgroup_preview_id','fk_campaigns_preview_id','errors','is_valid','generated_id','adwords_id','delete_from_adwords','fk_adwords_ad_id','update_hash'];
}