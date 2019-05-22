<?php
/**
 * Created by PhpStorm.
 * User: Jasper
 * Date: 21-5-2019
 * Time: 11:24
 */

    /*
     * Class DBConnection
     * Create a database connection using PDO
     * @author jonahlyn@unm.edu
     *
     * Instructions for use:
     *
     * require_once('settings.config.php');          // Define db configuration arrays here
     * require_once('DBConnection.php');             // Include this file
     *
     * $database = new DBConnection($dbconfig);      // Create new connection by passing in your configuration array
     *
     * $sqlSelect = "select * from .....";           // Select Statements:
     * $rows = $database->getQuery($sqlSelect);      // Use this method to run select statements
     *
     * foreach($rows as $row){
     * 		echo $row["column"] . "<br/>";
     * }
     *
     * $sqlInsert = "insert into ....";              // Insert/Update/Delete Statements:
     * $count = $database->runQuery($sqlInsert);     // Use this method to run inserts/updates/deletes
     * echo "number of records inserted: " . $count;
     *
     * $name = "jonahlyn";                          // Prepared Statements:
     * $stmt = $database->dbc->prepare("insert into test (name) values (?)");
     * $stmt->execute(array($name));
     *
     */

Class DBConnection {
    // Database Connection Configuration Parameters
    //
    protected $_config;
    // Database Connection
    public $dbc;
    /* function __construct
     * Opens the database connection
     * @param $config is an array of database connection parameters
     */
    public function __construct( array $config = array('driver' => 'mysql','host' => 'localhost','dbname' => 'nerdygadgets','username' => 'root','password' => '')) {
        $this->_config = $config;
        $this->getDBConnection();
    }
    /* Function __destruct
     * Closes the database connection
     */
    public function __destruct() {
        $this->dbc = NULL;
    }
    /* Function getPDOConnection
     * Get a connection to the database using PDO.
     */
    private function getDBConnection() {
        // Check if the connection is already established
        if ($this->dbc == NULL) {
            // Create the connection
            $dsn = "" .
                $this->_config['driver'] .
                ":host=" . $this->_config['host'] .
                ";dbname=" . $this->_config['dbname'];
            try {
                $this->dbc = new PDO( $dsn, $this->_config[ 'username' ], $this->_config[ 'password' ] );
            } catch( PDOException $e ) {
                echo __LINE__.$e->getMessage();
            }
        }
    }
}