<?php
/**
 * Created by PhpStorm.
 * User: faisal-pc
 * Date: 21/08/2019
 * Time: 6:54 PM
 */
header("Cache-Control: no-store, no-cache, must-revalidate");
ini_set('display_errors', 0);
include("connection/pg_connection.php");

class fetchGridDAL extends pg_connection
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
    function fetchGrid($connect)
    {
        /*Offset Columns/Rows*/
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = (($page - 1) * $rows);

        $user_name = $_REQUEST["name_search"];
        $days = $_REQUEST["days_Search"];
        if ($days == '%') {
            $query_parm = 'o.order_date' . ' LIKE ' . '"%"';
        } else if ($days == 'last_7_days') {
            $query_parm = '(o.order_date >= (NOW() - INTERVAL 7 DAY))';
        } else if ($days == 'today') {
            $query_parm = 'DATE(o.order_date) = CURDATE()';
        } else {
            echo json_encode(array());
            return;
        }

        $sql = "SELECT o.order_id,o.quantity,o.total_price,o.order_date, u.user_name, p.product_name, p.product_price
                        FROM tbl_order as o
                        LEFT JOIN tbl_user u
                        ON o.user_id = u.user_id
                        LEFT JOIN tbl_product p
                        ON o.product_id = p.product_id
                        WHERE " . $query_parm . " and u.user_name LIKE '" . $user_name . "' and o.is_active = 0 ORDER BY o.order_date limit $rows offset $offset";

        $countSql = "SELECT COUNT(*) as count FROM tbl_order as o WHERE " . $query_parm . " and o.is_active = 0 ORDER BY o.order_date";

        $query = mysqli_query($connect, $sql);
        $row = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $countQuery = mysqli_query($connect, $countSql);
        $countRow = mysqli_fetch_assoc($countQuery);

        if ($row) {
            $res["total"] = $countRow["count"];
            $res["rows"] = $row;
            echo json_encode($res);
        } else {
            echo json_encode(array());
        }
    }
}

$fetchGrid = new fetchGridDAL();
$fetchGrid->closeConnection($connect);

