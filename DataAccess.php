<?php
    class ServerType
    {   
        static $MYSQL = 0;
        static $ODBC = 1;
        static $ORACLE = 2;
        static $MS_SQLSERVER = 3;
    }
    
    class RecordType
    {
        static $OBJECT = 3;
        static $NUMERIC = 2;
        static $ASSOCIATIVE = 1;
        static $ASSOC_NUM = 0;
        
        
    }
    
    class DataAccess
    {               
        private $configName;
        private $databasename;
        private $servername;
        private $username;
        private $password;
        private $queryCount;
        private $lastResultset;
        private $recordType;
        
        
        
        private $connection;
        
        function __construct($configName)
        {
            $this->configName = $configName;
            $this->recordType = 0;
            $this->queryCount = 0;
            $this->servername = "localhost" ;
            $this->username = "root";
            $this->password = "" ;
            $this->databasename = "hackathon_peer";    
            $this->connect();
            
          /*  $xmlDoc = new DOMDocument();
            $xmlDoc->load("config.xml");
              
            $configurations = $xmlDoc->getElementsByTagName("configuration");
            
            if($configName == "default")
            {
                $configuration = $xmlDoc->getElementsByTagName("default");
                $node =  $configuration->item(0)->getElementsByTagName("servername");
                $this->servername = $node->item(0)->nodeValue;
                
                $node =  $configuration->item(0)->getElementsByTagName("username");
                $this->username = $node->item(0)->nodeValue;
                
                $node =  $configuration->item(0)->getElementsByTagName("password");
                $this->password = $node->item(0)->nodeValue;
                
                $node =  $configuration->item(0)->getElementsByTagName("database");
                $this->databasename = $node->item(0)->nodeValue;
                
                $this->connect();
            }
            
            foreach( $configurations as $configuration )
            {
                if($configName == $configuration->getAttribute("name"))
                {
                    $node =  $configuration->getElementsByTagName("servername");
                    $this->servername = $node->item(0)->nodeValue;
                    
                    $node =  $configuration->getElementsByTagName("username");
                    $this->username = $node->item(0)->nodeValue;
                    
                    $node =  $configuration->getElementsByTagName("password");
                    $this->password = $node->item(0)->nodeValue;
                    
                    $node =  $configuration->getElementsByTagName("database");
                    $this->databasename = $node->item(0)->nodeValue;
                    
                    $this->connect();
                }
            }
            if($this->servername == "")
            {
                $configuration = $xmlDoc->getElementsByTagName("default");
                $node =  $configuration->item(0)->getElementsByTagName("servername");
                $this->servername = $node->item(0)->nodeValue;
                
                $node =  $configuration->item(0)->getElementsByTagName("username");
                $this->username = $node->item(0)->nodeValue;
                
                $node =  $configuration->item(0)->getElementsByTagName("password");
                $this->password = $node->item(0)->nodeValue;
                
                $node =  $configuration->item(0)->getElementsByTagName("database");
                $this->databasename = $node->item(0)->nodeValue;
                
                $this->connect();
            }*/
        }
        
        private function connect()
        {
            $this->connection = mysql_connect($this->servername, $this->username, $this->password);
            if(!$this->connection)
            {
                echo "Error connection database server";
                exit();
            }
            if(!mysql_select_db($this->databasename, $this->connection))
            {
                echo "Error loading database";
                exit();
            }
            return;
        }
        
        public function getConfigName()
        {
            return $this->configName;
        }
        
        public function getConnection()
        {
            return $this->connection;
        }
        
        public function getServerName()
        {
            return $this->servername;
        }
        
        public function getUsername()
        {
            return $this->username;
        }
        
        public function getDatabasename()
        {
            return $this->databasename;
        }
        
        public function getQueryCount()
        {
            return $this->queryCount;
        }
        
        public function getRecordType()
        {
            return $this->recordType;
        }
        
        public function getLastResultSet()
        {
            return $this->lastResultset;
        }
        
        public function setRecordType($recordType)
        {
            $this->recordType = $recordType;
        }
        
        public function getEscapedString($str)
        {
            return mysql_real_escape_string($str, $this->connection);
        }
        
    public function getResultSet($sql)
        {
            if($this->lastResultset = mysql_query($sql))
            {
                $this->queryCount++;
            }
            else
            {
                echo 'Error querying database. '. mysql_error();
            }
            
            return $this->lastResultset;
        }
        
        public function executeQuery($sql)
        {
            $this->queryCount++;
            return mysql_query($sql);
            
        }
        
        public function getRow($resultSet)
        {
            if($this->recordType == RecordType::$ASSOC_NUM)
            {
                return mysql_fetch_array($resultSet);
            }
            else if($this->recordType == RecordType::$ASSOCIATIVE)
            {
                return mysql_fetch_assoc($resultSet);
            }
            else if($this->recordType == RecordType::$NUMERIC)
            {
                return mysql_fetch_row($resultSet);
            }
            else if($this->recordType == RecordType::$OBJECT)
            {
                return mysql_fetch_object($resultSet);
            }
        }
        
        public function countRows($resultSet)
        {
            return mysql_num_rows($resultSet);
        }
        
        public function dispose()
        {
            if($this->lastResultset != NULL)
            {
                mysql_free_result($this->lastResultset);
            }
            mysql_close($this->connection);
        }
    }
?>