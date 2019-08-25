<?php
/**
 * Created by PhpStorm.
 * User: faisal-pc
 * Date: 20/08/2019
 * Time: 3:06 PM
 */
header("Cache-Control: no-store, no-cache, must-revalidate");
ini_set('display_errors', 0);
include("connection/pg_connection.php");

class getUserAndProductsDAL extends pg_connection
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

    /*Select User for Lookup*/
    function getUserLookup($connect)
    {
        $sql = "SELECT user_id, user_name FROM tbl_user order by user_name;";
        $result = mysqli_query($connect, $sql);
        $resArray = array();
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($resArray, ($row));
            }
            echo json_encode($resArray);
        }
    }

    /*Select Product for Lookup*/
    function getProductLookup($connect)
    {
        $sql = "SELECT product_id, product_name FROM tbl_product order by product_name;";
        $result = mysqli_query($connect, $sql);
        $resArray = array();
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($resArray, ($row));
            }
            echo json_encode($resArray);
        }
    }
}

$getUserAndProductsDAL = new getUserAndProductsDAL();
$getUserAndProductsDAL->closeConnection($connect);