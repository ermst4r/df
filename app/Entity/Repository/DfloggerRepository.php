<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 08-02-17
 * Time: 20:07
 */

namespace App\Entity\Repository;


use App\Entity\Csvmapping;
use App\Entity\Dflogger;
use App\Entity\Repository\Contract\iCsvMapping;
use App\Entity\Repository\Contract\iDflogger;


class DfloggerRepository  extends Repository implements iDflogger
{




    /**
     * @param string $start_date
     * @param string $end_date

     */
    public function getLogMessage($start_date = '', $end_date = '')
    {
        if($start_date == '' && $end_date == '') {
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
        } else {
            $start_date = $start_date. ' 00:00:00';
            $end_date = $end_date. ' 23:59:59';
        }

        return   $this->model
            ->whereBetween('time',array($start_date,$end_date))
            ->get();

    }


}