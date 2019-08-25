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

class addOrderDAL extends pg_connection
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

    /*Add Order*/
    function addOrder($connect)
    {
        $json_data = $_REQUEST['formData'];
        $user_id = "";
        $product_id = "";
        $quantity_val = "";

        $jsonIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator(json_decode($json_data, TRUE)),
            RecursiveIteratorIterator::SELF_FIRST);

        $arr_controls = array();
        $arr_vals = array();
        foreach ($jsonIterator as $key => $val) {
            if (is_array($val)) {

            } else {
                if ($key == 'name') {
                    array_push($arr_controls, $val);
                } elseif ($key == 'value') {
                    array_push($arr_vals, $val);
                }
            }
        }

        for ($x = 0; $x <= sizeof($arr_controls); $x++) {
            if ($arr_controls[$x] == 'cmb_user') {
                $user_id = $arr_vals[$x];
            } elseif ($arr_controls[$x] == 'cmb_product') {
                $product_id = $arr_vals[$x];
            } elseif ($arr_controls[$x] == 'quantity_txt') {
                $quantity_val = $arr_vals[$x];
            }
        }
        $selectSql = "Select product_price,product_name from tbl_product where product_id = " . $product_id . ";";
        $selectResult = mysqli_query($connect, $selectSql);
        $selectResultRow = mysqli_fetch_array($selectResult);
        if ($selectResult->num_rows > 0) {
            $product_name = $selectResultRow['product_name'];
            $price = $selectResultRow['product_price'];
            if ($product_name == 'Pepsi Cola') {
                if ($quantity_val > 2) {
                    $discount = $price * 20 / 100;
                    $discounted_price = $price - $discount;
                    $total_price = $discounted_price * $quantity_val;
                } else {
                    $total_price = $price * $quantity_val;
                }
            } else {
                $total_price = $price * $quantity_val;
            }

            $insert = "insert into tbl_order(user_id, product_id, quantity, total_price, is_active)values ('" . $user_id . "','" . $product_id . "','" . $quantity_val . "','" . $total_price . "',0);";
            if (mysqli_query($connect, $insert)) {
                echo json_encode(array("result" => "Order Added Successfully"));
            } else {
                echo json_encode(array("result" => "Error"));
            }
        }
    }
}

$addOrderDAL = new addOrderDAL();
$addOrderDAL->closeConnection($connect);