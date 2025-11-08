<?php
/**
 * PostgreSQL class
 */

class PostgreSQL
{
    private $connection = NULL;
    public function __construct() {}

    public function connect($server, $port, $user, $password, $dbName)
    {
        $connString = "host=$server port=$port user=$user password=$password dbname=$dbName";
        $this->connection = pg_connect($connString);
        if (! $this->connection) {
            throw new Exception("ERROR: Unable to connect to PostgreSQL server on host " . $server . " port " . $port . " database " . $dbName  . ". Please contact administrator.");
        }
        $result = pg_exec($this->connection, "SET datestyle = ISO"); // set to iso date
        if (! $result) {
            $error = pg_last_error($this->connection);
            throw new Exception($error);
        }
    }
    public function query($sqlString)
    {
        $result = pg_query($this->connection, $sqlString);
        if (! $result) {
            $error = pg_last_error($this->connection);
            throw new Exception($error);
        }
        $records = array();
        while (($record = pg_fetch_assoc($result)) != null) {
            $records[] = $record;
        }
        return $records;
    }
    public function insert($sqlString)
    {
        $result = pg_exec($this->connection, trim($sqlString));
        if (! $result) {
            $error = pg_last_error($this->connection);
            throw new Exception($error);
        }
        $rows = pg_affected_rows($result);
        return $rows;
    }
    public function update($sqlString)
    {
        $result = pg_exec($this->connection, trim($sqlString));
        if (! $result) {
            $error = pg_last_error($this->connection);
            throw new Exception($error);
        }
        $rows = pg_affected_rows($result);
        return $rows;
    }
    public function delete($sqlString)
    {
        $result = pg_exec($this->connection, trim($sqlString));
        if (! $result) {
            $error = pg_last_error($this->connection);
            throw new Exception($error);
        }
        $rows = pg_affected_rows($result);
        return $rows;
    }
    public function beginTransaction()
    {
        $this->query("SET TRANSACTION ISOLATION LEVEL READ COMMITTED;");
        $result = $this->query("BEGIN;");
    }
    public function commitTransaction()
    {
        $this->query("COMMIT;");
    }
    public function rollbackTransaction()
    {
        $this->query("ROLLBACK;");
    }
    public function close()
    {
        if (! pg_close($this->connection)) {
            $error = pg_last_error($this->connection);
            throw new Exception($error);
        } else {
            $this->connection = null;
        }
    }
}

class ExportOdooIpd {
    private $dao = NULL;
    // private $logger = NULL;
    private $databaseConfig = [
        'host' => '172.10.0.2',
        'port' => 5432,
        'user' => 'homc',
        'Encoding' => 'WIN874',
        'password' => 'homc',
        'database' => 'manarom',
    ];
    function __construct()
    {
        // Logger::configure ( __DIR__ . "log4php.xml" );
        // $this->logger= Logger::getLogger ( $this->serviceName );        
        set_time_limit(0); // set 0 to unlimit but default max_execution_time = 300 ; Maximum execution time of each script, in seconds
        ini_set("max_execution_time", "0");
        ini_set("memory_limit", "1024M");
        $this->dao = new PostgreSQL();
        try {
            // $this->dao->connect ( $this->config ['host'], $this->config ['port'], $this->config ['user'], $this->config ['password'], $this->config ['database'] );
            $this->dao->connect(
                $this->databaseConfig['host'],
                $this->databaseConfig['port'],
                $this->databaseConfig['user'],
                $this->databaseConfig['password'],
                $this->databaseConfig['database']
            );
            // $this->logger->debug ( "connect to host:" . $this->config ['host'] . " port:" . $this->config ['port'] . " user:" . $this->config ['user'] . " password:" . $this->config ['password'] . " database:" . $this->config ['database'] . " SUCCESS" );
        } catch (Exception $e) {
            // $this->logger->debug ( $e->getMessage () );
            throw new Exception("ERROR: Unable to connect to PostgreSQL server on host " . $this->databaseConfig['host'] . " port " . $this->databaseConfig['port'] . " database " . $this->databaseConfig['database']  . ". Please contact administrator.");
        }
    }

    function __destruct()
    {
        // $this->logger->debug ( "Destructing " . $this->serviceName );
        $this->dao->close();
        $this->dao = null;
    }

    private function getCompany()
    {
        $sql = "SELECT * FROM company";
        $result = $this->dao->query($sql);
        return $result;
    }

    private function IpdReceiptHeaderHistory($company)
    {
        $today = $company['today'];
        $yesterday = $company['lastday'];
        $sql = "SELECT * FROM receipth WHERE recpdate = '{$yesterday}' AND recpstatusflag = 'I'";
        $result = $this->dao->query($sql);
        return $result;
    }

    private function IpdReceiptD($company, $receipt_header)
    {
        
    }

    public function run()
    {
        $this->dao->beginTransaction();
        $company = $this->getCompany();
        //  print_r ( $comany   );
        $receipt_header =  $this->IpdReceiptHeaderHistory($company[0]);
        print_r ( $receipt_header );
        $this->dao->commitTransaction();
    }
}