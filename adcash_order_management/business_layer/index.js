/**
 * Created by faysal on 4/2/2018.
 */
$(document).ready(function () {
    //Get user list
    get_user();
    //Get product list
    get_product();
    //Show Grid
    showGridTable();
});

let user_error_msg = "";
let product_error_msg = "";
let quantity_error_msg = "";
let error_status = 0;

//get user
function get_user() {
    document.getElementById("cmb_user").innerHTML = "";
    addItem("NA", "[Select User]", "cmb_user");

    $.ajax({
        url: "data_access_layer/getUserAndProductsDAL.php?method=getUserLookup",
        type: "GET",
        dataType: 'json',
        async: false,
        success: function callback(response) {
            let data = [];
            data = response;
            if (data.error) {
            } else {
                for (let i = 0; i < data.length; i++) {
                    //adding data into the combo box ,
                    addItem(data[i].user_id, data[i].user_name, "cmb_user");
                }
            }
        }
    });
}

//get product
function get_product() {
    document.getElementById("cmb_product").innerHTML = "";
    addItem("NA", "[Select Product]", "cmb_product");

    $.ajax({
        url: "data_access_layer/getUserAndProductsDAL.php?method=getProductLookup",
        type: "GET",
        dataType: 'json',
        async: false,
        success: function callback(response) {
            let data = [];
            data = response;
            for (let i = 0; i < data.length; i++) {
                //adding data into the combo box ,
                addItem(data[i].product_id, data[i].product_name, "cmb_product");
            }
        }
    });
}

/*Add Order*/
$("#btn_add_order").click(function () {
    if (validation() > 0) {
        showError();
    } else {
        let formData = JSON.stringify($("#add_order_form").serializeArray());
        $.ajax({
            url: "data_access_layer/add/addOrderDAL.php?method=addOrder&formData=" + formData,
            type: "POST",
            async: false,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function callback(response) {
                if (response.result == "Order Added Successfully") {
                    $("#modalTitle").html("SUCCESS");
                    $("#modelBody").html(response.result);
                    $("#alert_modal").modal();
                    $('#add_order_form').trigger("reset");
                    showGridTable();
                } else {
                    $("#modalTitle").html("Try Again.");
                    $("#modelBody").html(response.result);
                    $("#alert_modal").modal();
                }
            }
        });
    }
});

/*Advance search */
$("#btn_advance_Search").click(function () {
    showGridTable();
});

/*Fields Validation*/
function validation() {
    //====Validation====
    error_status = 0;
    //user drop down
    let chk_objective = /^[A-Za-z0-9 @.-_]{3,20}$/;///^[a-zA-Z]+$/;
    let txt_objective = "";
    txt_objective = $("#cmb_user option:selected").val();
    if (txt_objective == "NA") {
        error_status = 1;
        user_error_msg = "Select User.";
        $("#cmb_user").css("background", "#FFCBCB");
        $("#cmb_user").css("borderColor", "#FF0000");
    } else {
        user_error_msg = "";
        $("#cmb_user").css("background", "");
        $("#cmb_user").css("borderColor", "");
        $("#cmb_user_error").html(user_error_msg);
    }

    //product drop down
    txt_objective = $("#cmb_product option:selected").val();
    if (txt_objective == "NA") {
        error_status = 1;
        product_error_msg = "Select Product.";
        $("#cmb_product").css("background", "#FFCBCB");
        $("#cmb_product").css("borderColor", "#FF0000");
    } else {
        product_error_msg = "";
        $("#cmb_product").css("background", "");
        $("#cmb_product").css("borderColor", "");
        $("#cmb_product_error").html(product_error_msg);
    }

    txt_objective = $("#quantity_txt").val().trim();
    if (txt_objective.trim().length == 0) {
        error_status = 1;
        quantity_error_msg = "Quantity Box cannot be empty";
        $("#quantity_txt").css("background", "#FFCBCB");
        $("#quantity_txt").css("borderColor", "#FF0000");
    } else {
        quantity_error_msg = "";
        $("#quantity_txt").css("background", "");
        $("#quantity_txt").css("borderColor", "");
        $("#quantity_txt_error").html(quantity_error_msg);
    }
    return error_status;
}

/*Fields Error*/
function showError() {
    $("#cmb_user_error").html(user_error_msg);
    $("#cmb_product_error").html(product_error_msg);
    $("#quantity_txt_error").html(quantity_error_msg);
    return false;
}

/*Data Grid*/
function showGridTable() {
    let name = $("#name_search_txt").val();
    let days_search = $("#cmb_days_search option:selected").val();
    // console.log(days_search);
    if (name == '' || name == "") {
        name = "%";
    }
    $('#form_data').datagrid({
        // title: 'Editable DataGrid',
        url: 'data_access_layer/fetchGridDAL.php?method=fetchGrid&days_Search=' + days_search + '&name_search=' + name,
        idField: 'order_id',
        pagination: true,
        singleSelect: true,
        striped: true,
        rownumbers: true,
        fitColumns: false,
        nowrap: true,
        iconCls: 'icon-edit',
        columns: [[
            {field: 'user_name', title: 'User', width: 120},
            {
                field: 'product_name', title: 'Product', width: 120, editor: {
                    type: 'combobox',
                    options: {
                        valueField: 'product_name',
                        textField: 'product_name',
                        method: 'get',
                        url: 'data_access_layer/getUserAndProductsDAL.php?method=getProductLookup',
                        required: true
                    }
                }
            },
            {field: 'product_price', title: 'Price (EUR)', width: 80},
            {field: 'quantity', title: 'Quantity', width: 80, editor: {type: 'numberbox'}},
            {field: 'total_price', title: 'Total (EUR)', width: 70},
            {field: 'order_date', title: 'Date', width: 120},
            {
                field: 'action', title: 'Actions', width: 140, align: 'center',
                formatter: function (value, row, index) {
                    if (row.editing) {
                        var s = '<a href="javascript:void(0)" onclick="updateOrder(this,' + index + ')">Update</a> ';
                        var c = '<a href="javascript:void(0)" onclick="cancelRow(this)">Cancel</a>';
                        return s + c;
                    } else {
                        var e = '<a href="javascript:void(0)" onclick="editOrder(this)">Edit</a> || ';
                        var d = '<a href="javascript:void(0)" onclick="deleteOrder(this)">Delete</a> ';
                        return e + d;
                    }
                }
            }
        ]],
        onEndEdit: function (index, row) {
        },
        onBeforeEdit: function (index, row) {
            row.editing = true;
            $(this).datagrid('refreshRow', index);
        },
        onAfterEdit: function (index, row) {
            row.editing = false;
            $(this).datagrid('refreshRow', index);
        },
        onCancelEdit: function (index, row) {
            row.editing = false;
            $(this).datagrid('refreshRow', index);
        }

    });
}

function getRowIndex(target) {
    var tr = $(target).closest('tr.datagrid-row');
    return parseInt(tr.attr('datagrid-row-index'));
}

function editOrder(target) {
    $('#form_data').datagrid('beginEdit', getRowIndex(target));
}

/*Update Order*/
function updateOrder(target, index) {
    $.messager.confirm('confirm', 'confirm updating?', function (row) {
        if (row) {
            $('#form_data').datagrid('endEdit', getRowIndex(target));
            var selectedRow = $('#form_data').datagrid('getSelected'); //Get selected row
            $.ajax({
                url: 'data_access_layer/edit/editOrderDAL.php?order_detail=' + JSON.stringify(selectedRow) + '&method=editOrder',
                success: function (response) {
                    if (response.result = 'Order updated successfully') {
                        $('#form_data').datagrid('endEdit', getRowIndex(target));
                        alert('Success');
                        $('#form_data').datagrid('reload');
                        return true;
                    }
                }, error: function (response) {
                    alert('Failed. Try Again');
                }
            });
        }
    })
}

/*Delete Order*/
function deleteOrder(target) {
    $.messager.confirm('confirm', 'confirm deleting?', function (row) {
        if (row) {
            $('#form_data').datagrid('endEdit', getRowIndex(target));
            var selectedRow = $('#form_data').datagrid('getSelected'); //Get selected row
            $.ajax({
                url: 'data_access_layer/delete/deleteOrderDAL.php?order_id=' + selectedRow.order_id + '&method=deleteOrder',
                success: function (response) {
                    if (response.result = 'Record updated successfully') {
                        $('#form_data').datagrid('endEdit', getRowIndex(target));
                        alert('Success');
                        $('#form_data').datagrid('reload');
                        return true;
                    }
                }, error: function (response) {
                    alert('Failed. Try Again');
                }
            });
        }
    })
}

function cancelRow(target) {
    $('#form_data').datagrid('cancelEdit', getRowIndex(target));
}

/*Items Add in Lookups*/
function addItem(cmb_id, cmb_text, option) {
    let opt = document.createElement("option");
    document.getElementById(option).options.add(opt);
    opt.text = cmb_text;
    opt.value = cmb_id;
}