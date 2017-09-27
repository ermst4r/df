<?php
namespace App\DfCore\DfBs\Adwords;
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


class AdwordsValidator
{
    /**
     * @var string
     */

    private $empty_string = 'empty';
    /**
     * @var string
     */
    private $length_exceeded = 'length_exceeded';
    /**
     * @var string
     */
    private $invalid_url = 'invalid_url';


    /**
     * @param $data
     * @return string
     */
    public function dispatchUpdateHash($data)
    {
        return md5($data['headline_1'].$data['headline_2'].$data['description'].$data['path_1'].$data['path_2'].$data['final_url'].$data['fk_adgroup_preview_id'].$data['fk_campaigns_preview_id']);
    }
    /**
     * @param $data
     * @return array
     */
    public  function validateAd($data)
    {
        $returnMessage = [];

        if(empty($data['headline_1'])) {
            $returnMessage['headline_1'][] = $this->empty_string;
        }

        if(strlen($data['headline_1']) > 30) {
            $returnMessage['headline_1'][] = $this->length_exceeded;
        }


        if(strlen($data['headline_2']) > 30) {
            $returnMessage['headline_2'][] = $this->length_exceeded;
        }

        if(empty($data['headline_2'])) {
            $returnMessage['headline_2'][] = $this->empty_string;
        }


        if(strlen($data['description']) > 80) {
            $returnMessage['description'][] = $this->length_exceeded;
        }

        if(empty($data['description'])) {
            $returnMessage['description'][] = $this->empty_string;
        }


        if(strlen($data['path_1']) > 15) {
            $returnMessage['path_1'][] = $this->length_exceeded;
        }

        if(strlen($data['path_2']) > 15) {
            $returnMessage['path_2'][] = $this->length_exceeded;
        }

        if(filter_var($data['final_url'],FILTER_VALIDATE_URL) === false) {
            $returnMessage['final_url'][] = $this->invalid_url;
        }


        return $returnMessage;

    }

}