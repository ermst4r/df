<?php

namespace App\DfCore\DfBs\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use PDO;
/**
 * Class Api
 * @package classes
 */
class DfbuilderLogger {

    /**
     * @var null
     */
    private $logger = null;

    /**
     * @return Logger
     */
    private function createLogger() {


        $hostname = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $db = env('DB_DATABASE');
        // Create the logger
        $db = new \PDO('mysql:host='.$hostname.';dbname='.$db, $username, $password);
        $logger = new Logger('dfbuilder_logger');
        // Now add some handlers
        $logger->pushHandler(new Logtodatabase($db));
        return $logger;

    }





    /**
     * @return Logger|null
     */
    public function getLogger() {

        if ($this->logger == null) {
            $this->logger = $this->createLogger();
        }

        return $this->logger;

    }
}