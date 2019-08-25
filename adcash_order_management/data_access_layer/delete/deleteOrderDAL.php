<?php
/**
 * Created by PhpStorm.
 * User: faisal-pc
 * Date: 20/08/2019
 * Time: 10:44 PM
 */
header("Cache-Control: no-store, no-cache, must-revalidate");
ini_set('display_errors', 0);
include("../connection/pg_connection.php");

class deleteOrderDAL extends pg_connection
{
    function __construct()
    {
        $connect = mysqli_connect($this->localhost, $this->username, $this->password, $this->dbname);
        if ($connect->connect_error) {
            echo json_encode(array("error" => "database connection failed."));
            return;
        }
        //Get Method Name and Param.
        $dynamicMethod = $_REQUEST['method'];
        $this->$dynamicMethod($connect);
    }

    /*Delete Order*/
    function deleteOrder($connect)
    {
        $order_id = $_REQUEST['order_id'];
        $insert = "UPDATE tbl_order SET is_active= 1 WHERE order_id =" . $order_id . ";";
        if (mysqli_query($connect, $insert)) {
            echo json_encode(array("result" => "Order Deleted Successfully"));
        } else {
            echo json_encode(array("result" => "Error"));
        }
    }
}

$deleteOrderDAL = new deleteOrderDAL();
$deleteOrderDAL->closeConnection($connect);