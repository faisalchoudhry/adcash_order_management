<?php
/**
 * Created by PhpStorm.
 * User: faisal-pc
 * Date: 20/08/2019
 * Time: 12:20 PM
 */ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adcash Order Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <!--Easy Ui grid Link-->
    <link rel="stylesheet" type="text/css" href="presentation_layer/assets/libraries/easyui/css/easyui_new.css">
    <link rel="stylesheet" type="text/css" href="presentation_layer/assets/libraries/easyui/css/icon.css">
    <link rel="stylesheet" href="presentation_layer/assets/libraries/bootstrap/css/bootstrap.css"/>

    <script src="presentation_layer/assets/libraries/jquery/jquery-3.3.1.min.js"></script>
    <script src="presentation_layer/assets/libraries/bootstrap/js/bootstrap.js"></script>

    <style>
        .padding-left-right-remove {
            padding-right: 0px;
            padding-left: 0px;
        }

        .padding-left-right-remove-10 {
            padding-right: 5px;
            padding-left: 5px;
        }

        .margin-left-right-remove {
            margin-left: 0px;
            margin-right: 0px;
        }

        .datagrid-header .datagrid-cell span {
            font-weight: bold;
            /*color: blue;*/
            /*font-size:18px;*/
        }

        .datagrid-wrap {
            padding: 0px;
        }

        .datagrid {
            margin-bottom: 0px;
        }

        .datagrid-header-row > td {
            background-color: #4ba3ae;
        }

        .datagrid-header-row {
            height: 40px;
        }

        .datagrid-header {
            height: 40.8px;
        }
    </style>
</head>
<body>
<div class="row margin-left-right-remove">
    <div>
        <h1 style="text-align: center">AdCash Order Management</h1>
    </div>
    <div class="col-md-6 col-md-push-3 panel panel-primary padding-left-right-remove" style="margin-top: 15px">
        <div class="panel-heading" style="color:#fff;background: #0098C0;">
            <h3 class="panel-title">Add new order</h3>
        </div>
        <div class="panel-body">
            <form id="add_order_form" action="#" method="post" role="form"
                  enctype="multipart/form-data">
                <br>
                <div class="form-group">
                    <div class="row" style="margin-right: 0; margin-left: 0">
                        <!--User-->
                        <div>
                            <div class="input-group">
                                <div class="input-group-addon">User</div>
                                <select class="form-control formControlWIdth" name="cmb_user"
                                        id="cmb_user" onchange="" style="">
                                </select>
                            </div>
                            <div id="cmb_user_error" class="invalid-feedback text-danger"></div>
                        </div>
                        <br>
                        <!--Product-->
                        <div>
                            <div class="input-group">
                                <div class="input-group-addon">Product</div>
                                <select class="form-control formControlWIdth" name="cmb_product"
                                        id="cmb_product" onchange="" style="">

                                </select>
                            </div>
                            <div id="cmb_product_error" class="invalid-feedback text-danger"></div>
                        </div>
                        <br>
                        <!--Quantity-->
                        <div class="input-group">
                            <div class="input-group-addon">Quantity</div>
                            <input type="number" min="1" class="form-control" name="quantity_txt"
                                   id="quantity_txt"
                                   value=""
                                   placeholder="Enter Quantity.." style="height:34px" required>
                        </div>
                        <div id="quantity_txt_error" class="invalid-feedback text-danger"></div>
                    </div>
                </div>
                <!--Sub Role Id hidden-->
                <!--                <input type="text" class="form-control hidden" name="assign_id_txt" id="assign_id_txt"-->
                <!--                       placeholder="" style="height:34px">-->
                <div class="form-group" style="text-align: right">
                    <button type="button" class="btn btn-default" id="btn_add_order">Add
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row margin-left-right-remove">
    <div class="col-md-6 col-md-push-3 panel panel-primary padding-left-right-remove" style="margin-top: 5px">
        <div class="panel-heading" style="color:#fff;background: #0098C0;">
            <h3 class="panel-title">Search</h3>
        </div>
        <div class="panel-body">
            <form id="assign_menus_form" action="#" method="post" role="form"
                  enctype="multipart/form-data">
                <br>
                <div class="form-group">
                    <div class="row" style="margin-right: 0; margin-left: 0">
                        <!--Days Search-->
                        <div class="col-md-6" style="padding: 5px">
                            <div class="input-group">
                                <div class="input-group-addon">Search</div>
                                <select class="form-control formControlWIdth" name="cmb_days_search"
                                        id="cmb_days_search">
                                    <option value="%">All time</option>
                                    <option value="last_7_days">Last 7 days</option>
                                    <option value="today">Today</option>
                                </select>
                            </div>
                        </div>
                        <!--Search by user or product-->
                        <div class="col-md-6" style="padding: 5px">
                            <div class="input-group">
                                <div class="input-group-addon">By Name</div>
                                <input type="text" class="form-control" name="name_search_txt" id="name_search_txt"
                                       placeholder="Enter name.." style="height:34px" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Search Button-->
                <div class="form-group" style="text-align: right">
                    <button type="button" class="btn btn-default" id="btn_advance_Search">Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Data GRID-->
<div class="row margin-left-right-remove">
    <div class="col-md-6 col-md-push-3 panel panel-primary padding-left-right-remove">
        <h5 style="text-align: center;font-weight: bold">Order List</h5>
        <div id="form_data" style="overflow: auto;padding: 0">
        </div>
    </div>
</div>


<!--alert Modal-->
<div class="modal fade" id="alert_modal" role="dialog">
    <div class="modal-dialog myModelDialog">
        <!-- Modal content-->
        <div class="modal-content" style="border-radius: 0;">
            <div class="modal-header" style="padding: 0; background: #0098c0;">
                <button type="button" class="close" style="color: #fff;" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modalTitle"
                    style="padding-top: 8px; padding-left: 10px; color: #fff;"></h4>
            </div>
            <div class="modal-body" id="modelBody" style="overflow-x:auto">

            </div>
            <div class="modal-footer">
                <button style="color: #fff;background-color: #0098c0" type="button btn-default"
                        class="btn btncustomecss"
                        data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="business_layer/index.js"></script>
<!--Easyui links-->
<script type="text/javascript" src="presentation_layer/assets/libraries/easyui/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="presentation_layer/assets/libraries/easyui/js/jquery.datagrid.js"></script>
<script type="text/javascript" src="presentation_layer/assets/libraries/easyui/js/datagrid-filter.js"></script>
</body>
</html>