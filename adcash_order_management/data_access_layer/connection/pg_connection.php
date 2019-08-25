<?php

class pg_connection
{
    public $localhost = 'localhost';
    public $dbname = 'db_adcash_order_management_app';
    public $username = 'root';
    public $password = '';

    public function closeConnection($connect)
    {
        mysqli_close($connect);
    }
}

$con = new pg_connection();

