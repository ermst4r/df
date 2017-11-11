<?php
namespace App\Entity\Repository\Contract;

interface iDflogger {



    public function getLogMessage($start_date='',$end_date='');
}