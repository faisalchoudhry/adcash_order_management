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

class editOrderDAL extends pg_connection
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

    /*Edit Order*/
    function editOrder($connect)
    {
        $order_detail = json_decode($_REQUEST['order_detail'], true);
        $price = $order_detail[product_price];
        if ($order_detail[product_name] == 'Pepsi Cola') { /*Promotional Discount*/
            if ($order_detail[quantity] > 2) {
                $discount = $price * 20 / 100;
                $discounted_price = $price - $discount;
                $total_price = $discounted_price * $order_detail[quantity];
            } else {
                $total_price = $price * $order_detail[quantity];
            }
        } else {
            $total_price = $price * $order_detail[quantity];
        }
        $insert = "UPDATE tbl_order SET product_id = (select product_id from tbl_product where product_name = '" . $order_detail[product_name] . "'), 
                            quantity = '" . $order_detail[quantity] . "', 
                            total_price = '" . $total_price . "' 
                            WHERE order_id =" . $order_detail[order_id] . ";";
        if (mysqli_query($connect, $insert)) {
            echo json_encode(array("result" => "Order Deleted Successfully"));
        } else {
            echo json_encode(array("result" => "Error"));
        }
    }
}

$editOrderDAL = new editOrderDAL();
$editOrderDAL->closeConnection($connect);