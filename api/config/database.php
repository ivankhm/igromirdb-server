<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/20/2017
 * Time: 9:17 PM
 */

class Database {
    private  $host = 'localhost';
    private  $db_name = 'igromirbd';
    private  $username = 'root';
    private  $password = '';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try
        {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            //var_dump($this->conn);
            //$this->exec('set names uft8');
        }
        catch (PDOException $exception)
        {
            echo 'Connection error: '. $exception->getMessage();
        }

        return $this->conn;
    }
}

function echoMessage($message)
{
    echo '{';
        echo '"message": "'.$message.'"';
    echo '}';
}