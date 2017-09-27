<?php
namespace App\DfCore\DfBs\Log;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class Logtodatabase extends AbstractProcessingHandler
{
    private $initialized = false;
    private $pdo;
    private $statement;

    public function __construct(\PDO $pdo, $level = Logger::DEBUG, $bubble = true)
    {
        $this->pdo = $pdo;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        $data['channel'] = $record['channel'];
        $data['level'] = $record['level'];
        $data['message'] = $record['message'];
        $data['time'] = date('Y-m-d H:i:s');

        $this->statement->execute($data);






    }

    private function initialize()
    {

//        $this->pdo->exec(
//            'CREATE TABLE IF NOT EXISTS df_logger '
//            .'(channel VARCHAR(255), level INTEGER, message LONGTEXT, time DATETIME)'
//        );
        $this->statement = $this->pdo->prepare(
            'INSERT INTO df_logger (channel, level,  message, time )
              VALUES (:channel, :level, :message, :time )'
        );


        $this->initialized = true;
    }
}