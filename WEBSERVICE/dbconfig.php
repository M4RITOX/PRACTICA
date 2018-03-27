<?php
    class dbconnection{
        private $DB_USER = "PRUEBA";
        private $DB_PASS = "123";
        private $DB_SERVER = "DESKTOP-LSHI6RI";
        private $DB_NAME = "TESTPHARMA";
        
        
        public function connect(){
            $serverName = "(local)\sqlexpress";

            try

            {
                $conn_str = "sqlsrv:Server=$this->DB_SERVER;Database=$this->DB_NAME";

                $conn = new PDO($conn_str, $this->DB_USER, $this->DB_PASS);
                //$conn = new PDO("sqlsrv:Server=DESKTOP-LSHI6RI;Database=TESTPHARMA", "PRUEBA", "123");
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                
                return $conn;

            }

            catch(Exception $e){

                die( print_r( $e->getMessage() ) );

            }
        }
        
        
        
        
    }
?>