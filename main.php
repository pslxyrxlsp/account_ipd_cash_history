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


/**
 * AccountIpdCashHistory class
 */

class AccountIpdCashHistory
{
    private $serviceName = "AccountIpdCashHistory";
    private $dao = NULL;
    // private $logger = NULL;
    private $databaseConfig = [
        'host' => '172.10.0.10',
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

    private function getYesterdaytDate(string $yyyymmdd): string
    {
        $beYear = intval(substr($yyyymmdd, 0, 4));
        $month = substr($yyyymmdd, 4, 2);
        $day = substr($yyyymmdd, 6, 2);
        $ceYear = $beYear - 543;
        $dateString = sprintf("%04d-%s-%s", $ceYear, $month, $day);
        $date = new DateTime($dateString);
        $date->modify('-1 day'); // subtract one day Or using $date->sub(new DateInterval('P1D')); //to subtract one day
        $newCeYear = (int)$date->format('Y');
        $newMonth = $date->format('m'); // 'm' gives month with leading zeros
        $newDay = $date->format('d');   // 'd' gives day with leading zeros
        $newBeYear = $newCeYear + 543;
        return sprintf("%04d%s%s", $newBeYear, $newMonth, $newDay);
    }

    private function getCompany()
    {
        $sql = "SELECT * FROM company";
        $result = $this->dao->query($sql);
        return $result;
    }

    private  function getAccountIpdCashHistory($company)
    {
        $today = $company['today'];
        // $yesterday = $this->getYesterdaytDate($today);
        // print_r( $comany );
        $yesterday = $company['lastday'];
        $data = [];
        $sql = "SELECT resfour.*,rtrim(t.titlename) || ' ' ||rtrim(p.firstName) || ' ' || rtrim(p.lastName) as ptfullname,
                (_2143110	+
                _2143250	+
                _4110000	+
                _4110000_1	+
                _4110300	+
                _4111000	+
                _4114000	+
                _4115000	+
                _4116000	+
                _4117000	+
                _4118200	+
                _4119100      +
                _4121000	+
                _4121100	+
                _4121200	+
                _4121300	+
                _4121355	+
                _4121360	+
                _4123000	+
                _4124000	+
                _4124000_1	+
                _4125000	+
                _4126000	+
                _4126000_1	+
                _4126000_2	+
                _4126100	+
                _4126500	+
                _4126700	+
                _4127000	+
                _4128300	+
                _4129000	+
                _4129000_1	+
                _4129100	+
                _4129110	+
                _4200100_1	+
                _4203000	+
                _4204000	+
                _4205000	+
                _4205050	+
                _4205100	+
                _4280100	+
                _4280200	+
                _4280500	+
                _4290100	+
                _4290200	+
                _4290300	+
                _4290400	+
                _4290500	+
                _4290600	+
                _4290620	+
                _4290630	+
                _4290650	+
                _4290651	+
                _4290700	+
                _4301000	+
                _4301100	+
                _4302000	+
                _4303000	+
                _4400000	+
                _4503000	+
                _4630600	+
                _6000000	) as total
                from
                (select resthree.recpdate,resthree.hn,resthree.recpstatusflag,
                SUM(_2143110) as _2143110,
                SUM(_2143250) as _2143250,
                SUM(_4110000) as _4110000,
                SUM(_4110000_1) as _4110000_1,
                SUM(_4110300_) as _4110300,
                SUM(_4111000) as _4111000,
                SUM(_4114000) as _4114000,
                SUM(_4115000) as _4115000,
                SUM(_4116000) as _4116000,
                SUM(_4117000) as _4117000,
                SUM(_4118200) as _4118200,
                SUM(_4119100) as _4119100,
                SUM(_4121000) as _4121000,
                SUM(_4121100) as _4121100,
                SUM(_4121200) as _4121200,
                SUM(_4121300) as _4121300,
                SUM(_4121355) as _4121355,
                SUM(_4121360) as _4121360,
                SUM(_4123000) as _4123000,
                SUM(_4124000) as _4124000,
                SUM(_4124000_1) as _4124000_1,
                SUM(_4125000) as _4125000,
                SUM(_4126000) as _4126000,
                SUM(_4126000_1) as _4126000_1,
                SUM(_4126000_2) as _4126000_2,
                SUM(_4126100) as _4126100,
                SUM(_4126500) as _4126500,
                SUM(_4126700) as _4126700,
                SUM(_4127000) as _4127000,
                SUM(_4128300) as _4128300,
                SUM(_4129000) as _4129000,
                SUM(_4129000_1) as _4129000_1,
                SUM(_4129100) as _4129100,
                SUM(_4129110) as _4129110,
                SUM(_4200100_1) as _4200100_1,
                SUM(_4203000) as _4203000,
                SUM(_4204000) as _4204000,
                SUM(_4205000) as _4205000,
                SUM(_4205050) as _4205050,
                SUM(_4205100) as _4205100,
                SUM(_4280100) as _4280100,
                SUM(_4280200) as _4280200,
                SUM(_4280500) as _4280500,
                SUM(_4290100) as _4290100,
                SUM(_4290200) as _4290200,
                SUM(_4290300) as _4290300,
                SUM(_4290400) as _4290400,
                SUM(_4290500) as _4290500,
                SUM(_4290600) as _4290600,
                SUM(_4290620) as _4290620,
                SUM(_4290630) as _4290630,
                SUM(_4290650) as _4290650,
                SUM(_4290651) as _4290651,
                SUM(_4290700) as _4290700,
                SUM(_4301000) as _4301000,
                SUM(_4301100) as _4301100,
                SUM(_4302000) as _4302000,
                SUM(_4303000) as _4303000,
                SUM(_4400000) as _4400000,
                SUM(_4503000) as _4503000,
                SUM(_4630600) as _4630600,
                SUM(_6000000) as _6000000,
                resthree.rxno,
                resthree.refer,
                resthree.maker,
                resthree.cashier_code,
                resthree.paytypecode,
                resthree.doccode,
                (resthree.docname || ' ' || resthree.doclname) as doctor
                from
                (select
                restwo.recpdate,restwo.hn,restwo.recpstatusflag,
                (case when restwo.acctcode = '2143110' then restwo.amount else 0.00 end ) as _2143110,
                (case when restwo.acctcode = '2143250' then restwo.amount else 0.00 end ) as _2143250,
                (case when restwo.acctcode = '4110000' then restwo.amount else 0.00 end ) as _4110000,
                (case when restwo.acctcode = '4110000-1' then restwo.amount else 0.00 end ) as _4110000_1,
                (case when restwo.acctcode = '4110300' then restwo.amount else 0.00 end ) as _4110300_,
                (case when restwo.acctcode = '4111000' then restwo.amount else 0.00 end ) as _4111000,
                (case when restwo.acctcode =  '4114000' then restwo.amount else 0.00 end ) as _4114000,
                (case when restwo.acctcode = '4115000' then restwo.amount else 0.00 end ) as _4115000,
                (case when restwo.acctcode = '4116000' then restwo.amount else 0.00 end ) as _4116000,
                (case when restwo.acctcode = '4117000' then restwo.amount else 0.00 end ) as _4117000,
                (case when restwo.acctcode = '4118200' then restwo.amount else 0.00 end ) as _4118200,
                (case when restwo.acctcode = '4119100' then restwo.amount else 0.00 end ) as _4119100,
                (case when restwo.acctcode = '4121000' then restwo.amount else 0.00 end ) as _4121000,
                (case when restwo.acctcode = '4121100' then restwo.amount else 0.00 end ) as _4121100,
                (case when restwo.acctcode = '4121200' then restwo.amount else 0.00 end ) as _4121200,
                (case when restwo.acctcode = '4121300' then restwo.amount else 0.00 end ) as _4121300,
                (case when restwo.acctcode = '4121355' then restwo.amount else 0.00 end ) as _4121355,
                (case when restwo.acctcode = '4121360' then restwo.amount else 0.00 end ) as _4121360,
                (case when restwo.acctcode = '4123000' then restwo.amount else 0.00 end ) as _4123000,
                (case when restwo.acctcode = '4124000' then restwo.amount else 0.00 end ) as _4124000,
                (case when restwo.acctcode = '4124000-1' then restwo.amount else 0.00 end ) as _4124000_1,
                (case when restwo.acctcode = '4125000' then restwo.amount else 0.00 end ) as _4125000,
                (case when restwo.acctcode = '4126100' then restwo.amount else 0.00 end ) as _4126100,
                (case when restwo.acctcode = '4126000' then restwo.amount else 0.00 end ) as _4126000,
                (case when restwo.acctcode = '4126000-1' then restwo.amount else 0.00 end ) as _4126000_1,
                (case when restwo.acctcode = '4126000-2' then restwo.amount else 0.00 end ) as _4126000_2,
                (case when restwo.acctcode = '4126500' then restwo.amount else 0.00 end ) as _4126500,
                (case when restwo.acctcode = '4126700' then restwo.amount else 0.00 end ) as _4126700,
                (case when restwo.acctcode = '4127000' then restwo.amount else 0.00 end ) as _4127000,
                (case when restwo.acctcode = '4128300' then restwo.amount else 0.00 end ) as _4128300,
                (case when restwo.acctcode = '4129000' then restwo.amount else 0.00 end ) as _4129000,
                (case when restwo.acctcode = '4129000-1' then restwo.amount else 0.00 end ) as _4129000_1,
                (case when restwo.acctcode = '4129100' then restwo.amount else 0.00 end ) as _4129100,
                (case when restwo.acctcode = '4129110' then restwo.amount else 0.00 end ) as _4129110,
                (case when restwo.acctcode = '4200100-1' then restwo.amount else 0.00 end ) as _4200100_1,
                (case when restwo.acctcode = '4203000' then restwo.amount else 0.00 end ) as _4203000,
                (case when restwo.acctcode =  '4204000' then restwo.amount else 0.00 end ) as _4204000,
                (case when restwo.acctcode = '4205000' then restwo.amount else 0.00 end ) as _4205000,
                (case when restwo.acctcode = '4205050' then restwo.amount else 0.00 end ) as _4205050,
                (case when restwo.acctcode = '4205100' then restwo.amount else 0.00 end ) as _4205100,
                (case when restwo.acctcode = '4280100' then restwo.amount else 0.00 end ) as _4280100,
                (case when restwo.acctcode = '4280200' then restwo.amount else 0.00 end ) as _4280200,
                (case when restwo.acctcode = '4280500' then restwo.amount else 0.00 end ) as _4280500,
                (case when restwo.acctcode = '4290100' then restwo.amount else 0.00 end ) as _4290100,
                (case when restwo.acctcode = '4290200' then restwo.amount else 0.00 end ) as _4290200,
                (case when restwo.acctcode = '4290300' then restwo.amount else 0.00 end ) as _4290300,
                (case when restwo.acctcode = '4290400' then restwo.amount else 0.00 end ) as _4290400,
                (case when restwo.acctcode = '4290500' then restwo.amount else 0.00 end ) as _4290500,
                (case when restwo.acctcode = '4290600' then restwo.amount else 0.00 end ) as _4290600,
                (case when restwo.acctcode = '4290620' then restwo.amount else 0.00 end ) as _4290620,
                (case when restwo.acctcode = '4290630' then restwo.amount else 0.00 end ) as _4290630,
                (case when restwo.acctcode = '4290650' then restwo.amount else 0.00 end ) as _4290650,
                (case when restwo.acctcode = '4290651' then restwo.amount else 0.00 end ) as _4290651,
                (case when restwo.acctcode = '4290700' then restwo.amount else 0.00 end ) as _4290700,
                (case when restwo.acctcode = '4301000' then restwo.amount else 0.00 end ) as _4301000,
                (case when restwo.acctcode = '4301100' then restwo.amount else 0.00 end ) as _4301100,
                (case when restwo.acctcode = '4302000' then restwo.amount else 0.00 end ) as _4302000,
                (case when restwo.acctcode = '4303000' then restwo.amount else 0.00 end ) as _4303000,
                (case when restwo.acctcode = '4400000' then restwo.amount else 0.00 end ) as _4400000,
                (case when restwo.acctcode = '4503000' then restwo.amount else 0.00 end ) as _4503000,
                (case when restwo.acctcode = '4630600' then restwo.amount else 0.00 end ) as _4630600,
                (case when restwo.acctcode = '6000000' then restwo.amount else 0.00 end ) as _6000000,
                restwo.refer,
                restwo.maker,
                restwo.cashier_code,
                restwo.paytypecode,restwo.doccode, restwo.docname, restwo.doclname,
                restwo.rxno
                from
                (select
                resone.recpdate,resone.hn,resone.recpstatusflag,
                ccode.acctcode,ccode.acctcode_desc,
                resone.refer,
                resone.maker,
                resone.cashier_code,
                resone.paytypecode,resone.doccode, resone.docname,resone.doclname,
                resone.rxno,
                sum( to_number(resone.amount::text,'99999999.99') ) as amount
                from
                (select rh.recpdate,rh.refer, rh.hn,rh.recpstatusflag,
                            rh.maker,rh.cashier_code,rd.amount,
                        rd.chr_code,rp.paytypecode,rh.recphid, d.doccode, dc.docname, dc.doclname,
                        d.rxno
                from
                receipth rh
                left join receiptd rd on rd.recphid = rh.recphid
                left join rpayment rp on rp.recphid = rh.recphid
                left join deptq_d d on d.hn = rh.hn and d.regNo = rh.regist_flag
                left join docc dc on dc.doccode = d.doccode
                where (rh.recpdate = '{$yesterday}') and rh.hn <> '5001355' and rd.amount is not null)
                as resone
                left join chr_code ccode on resone.chr_code = ccode.chr_code
                group by
                resone.recpdate,resone.hn,resone.recpstatusflag,
                ccode.acctcode,ccode.acctcode_desc,
                resone.refer,
                resone.maker,
                resone.cashier_code,
                resone.paytypecode, resone.doccode, resone.docname, resone.doclname, resone.rxno) restwo )resthree
                group by resthree.recpdate,resthree.hn,resthree.recpstatusflag,resthree.refer,
                resthree.maker,
                resthree.cashier_code,
                resthree.paytypecode,
                resthree.doccode,
                resthree.docname, resthree.doclname, resthree.rxno) resfour
                left join patient p on resfour.hn = p.hn
                left join ptitle t on p.titlecode = t.titlecode
                where resfour.recpstatusflag = 'I'
                order by recpdate 
            ";
        // $this->logger->debug($SQLDETAIL);
        $data = $this->dao->query($sql);
        $data = $this->functFixedDuplicate($data);
        // $this->logger->debug ( "-------------------------------------------------------------" );
        for ($i = 0; $i < count($data); $i++) {
            $fields = array_keys($data[$i]);
            // $this->logger->debug($fields);
            $values = array_map(function ($v) {
                // Add quotes for strings, leave numbers as is, handle NULL
                if (is_null($v) || $v === '') return "NULL";
                if (is_numeric($v)) return $v;
                // Escape single quotes for SQL
                return "'" . str_replace("'", "''", $v) . "'";
            }, array_values($data[$i]));
            $sql = "INSERT INTO ipdcash_history (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ");";
            $this->logger->debug($sql);
            // $this->dao->insert($sql);
        }
        return $data;
    }

    function removeAllDuplicates($array, $keyField = 'refer') {
        // Extract all values for the key field
        $allValues = array_column($array, $keyField);        
        // Count occurrences of each value
        $valueCounts = array_count_values($allValues);        
        // Keep only items where the value appears exactly once
        return array_filter($array, function($item) use ($valueCounts, $keyField) {
            return $valueCounts[$item[$keyField]] === 1;
        });
    }

    private function functFixedDuplicate($records) {
        $keyCount = [];
        $duplicateKeys = [];
        $allDuplicates = [];
        // Step 1: Count and assign key duplcate occurrences 
        foreach ($records as $record) {
            $key = $record['refer'];
            if (!isset($keyCount[$key])) {
                $keyCount[$key] = 0;
            }
            $keyCount[$key]++;            
            // If we've seen this key more than once, mark it as duplicate
            if ($keyCount[$key] > 1) {
                $duplicateKeys[$key] = true;
            }            
        }
        // Step 2: Collect all records with duplicate keys using duplicateKeys array
        foreach ($records as $record) {
            $key = $record['refer'];
            if (isset($duplicateKeys[$key])) {
                foreach ($record as $fieldName => $fieldValue) {
                    if (substr($fieldName, 0, 1) === '_') {
                        // Reset any field starting with underscore to 0
                        $record[$fieldName] = 0.00;
                    }
                }
                $allDuplicates[] = $record;
            }
        }
        usort($allDuplicates, function ($a, $b) {
            return strcmp($a['hn'], $b['hn']);
        });
        // fixed duplicates by refer using php refernec and update records 
        foreach ($allDuplicates as $key => &$duplicate) {
            // $sql = "SELECT * FROM bill_d LEFT JOIN chr_code ccode ON bill_d.charge_code = ccode.chr_code WHERE rxno = '{$duplicate['rxno']}'";
            $sql = "SELECT charge_code, ccode.acctcode, sum(amount::NUMERIC) AS amount
                    FROM bill_d
                        LEFT JOIN chr_code ccode ON bill_d.charge_code = ccode.chr_code
                    WHERE rxno = '{$duplicate['rxno']}' GROUP BY bill_d.charge_code, ccode.acctcode";
            $res = $this->dao->query($sql);
            $total = 0.00;
            foreach ($res as $fieldName => $r) {
                $amt = 0.00;
                $key_for_duplicate = '_' . trim($r['acctcode']);         
                $amt += trim($r['amount']) ?? 0.00;      
                $duplicate[$key_for_duplicate] = $amt; // Set the amount for the specific key
                $total += $amt;                                
            }
            $duplicate['total'] = $total;                    
        }
         // Step 3: remove all duplicates from the original records
        $uniqueRecords = $this->removeAllDuplicates($records, 'refer');       
        // Step 4: merge unique and modified duplicates then sort the result by refer
        $result = array_merge($uniqueRecords, $allDuplicates);
        usort($result, function ($a, $b) {
            return strcmp($a['refer'], $b['refer']);
        });
        return $result;
        // return $allDuplicates;
    }

    public function run()
    {
        $this->dao->beginTransaction();
        $company = $this->getCompany();
        //  print_r ( $comany   );
        $this->getAccountIpdCashHistory($company[0]);
        // print_r ( $result );
        $this->dao->commitTransaction();
    }
}
// run the application
print_r("Start " . date("Y-m-d H:i:s") . "\n");
print_r("-------------------------------------------------------------" . "\n");
try {
    $theApp = new AccountIpdCashHistory();
    $theApp->run();
    unset($theApp);
} catch (Exception $e) {
    print("Error: " . $e->getMessage());
    exit();
}
print_r("End " . date("Y-m-d H:i:s") . "\n");
print_r("-------------------------------------------------------------" . "\n");
