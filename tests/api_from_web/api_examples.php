<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 March 2018 at 18:53:08 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 1

--><!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>The HTML5 Herald</title>
    <meta name="description" content="Test API">
    <meta name="author" content="Aurora.systems">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <style>
        div {
            margin: auto;
            width: 600px;
            padding: 50px;
            text-align: center
        }

        table {
            width: 100%;
        }

        input {
            padding: 5px;
            width: 100%;
        }

        button {
            margin-top: 5px
        }

        #result {
            margin-top: 20px;
            border: 1px solid #ddd
        }
    </style>


</head>

<body>

<div>
    <table >


        <tr>
            <td>
                Url
            </td>
            <td>
                <input id="api_url" value="" style="width:400px">
            </td>
        </tr>
        <tr>
            <td>
                Handle
            </td>
            <td>
                <input id="api_handle" value="" style="width:400px">
            </td>
        </tr>
        <tr>
            <td>
                Key
            </td>
            <td>
                <input id="api_key" value="" style="width:400px">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button id="get_user_data">Get user data</button>
            </td>


        </tr>
        <tr>
            <td colspan="2">
                <button id="get_employee_data">Get employee data</button>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <button id="get_part_data_from_barcode">Get part data from barcode</button>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <button id="get_part_data">Get part data</button>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button id="get_location_data">Get location data</button>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <button id="search_location_by_code">Search location by code</button>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                <button id="search_part_by_code">Search part by code</button>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                <button id="audit_stock">Audit stock</button>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                <button id="set_picking_location">Set picking location</button>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                <button id="get_delivery_note">Get delivery note</button>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                <button id="get_delivery_note_items">Get delivery note items</button>
            </td>
        </tr>



    </table>
</div>
<div id="result">

</div>

<script>

    var api_url = '';


    $("#get_user_data").on('click',function () {
        get_user_data()
    });
    $("#get_employee_data").on('click',function () {
        get_employee_data()
    });
    $("#get_part_data").on('click',function () {
        get_part_data()
    });

    $("#get_part_data_from_barcode").on('click',function () {
        get_part_data_from_barcode()
    });


    $("#get_location_data").on('click',function () {
        get_location_data()
    });

    $("#search_location_by_code").on('click',function () {
        search_location_by_code()
    });

    $("#search_part_by_code").on('click',function () {
        search_part_by_code()
    });


    $("#audit_stock").on('click',function () {
        audit_stock()
    });

    $("#set_picking_location").on('click',function () {
        set_picking_location()
    });
    $("#get_delivery_note").on('click',function () {
        get_delivery_note()
    });

    $("#get_delivery_note_items").on('click',function () {
        get_delivery_note_items()
    });




    function search_location_by_code() {

        set_up_credentials();

        var action = 'search_location_by_code';
        var query = 'a';



        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("query", query)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }

    function search_part_by_code() {

        set_up_credentials();

        var action = 'search_part_by_code';
        var query = 'jbb';



        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("query", query)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }

    function get_part_data() {

        set_up_credentials();

        var action = 'get_part_data';
        var part_sku = 5285;



        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("part_sku", part_sku)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }

    function get_part_data_from_barcode() {

        set_up_credentials();

        var action = 'get_part_data_from_barcode';
        var barcode = 'JBB-01AWUK';



        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("barcode", barcode)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }


    function set_picking_location() {

        set_up_credentials();

        var action = 'set_as_picking_location';
        var location_key = 8;
        var part_sku = 2426;


        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("location_key", location_key)
        ajaxData.append("part_sku", part_sku)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });

    }


    function audit_stock() {

        set_up_credentials();

        var action = 'audit_stock';
        var location_key = 8;
        var part_sku = 2430;
        var qty = 20;

        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("location_key", location_key)
        ajaxData.append("part_sku", part_sku)
        ajaxData.append("qty", qty)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }


    function get_location_data(){

        set_up_credentials();

        var action = 'get_location_data';
        var location_key = 215;


        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("location_key", location_key)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });

    }


    function get_delivery_note(){
        set_up_credentials();

        var action = 'get_delivery_note_from_public_id';
        var public_id = 314944;


        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("public_id", public_id)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }






    function get_delivery_note_items(){
        set_up_credentials();

        var action = 'get_delivery_note_items';
        var delivery_note_key = 1478;


        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("delivery_note_key", delivery_note_key)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }


    function get_employee_data() {

        set_up_credentials();

        var action = 'get_employee_data';


        var ajaxData = new FormData();
        ajaxData.append("action", action)


        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }

    function get_user_data() {

        set_up_credentials();

        var action = 'get_user_data';
        var arguments = {}


        var ajaxData = new FormData();
        ajaxData.append("action", action)
        ajaxData.append("arguments", JSON.stringify(arguments))

        $.ajax({
            url: api_url, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {
                console.log(data)
            }, error: function () {
                console.log(data)
            }
        });


    }






    function set_up_credentials() {
        // You do this only once when read the credentials (qCode)
        $.ajaxSetup({
            data: {
                AUTH_KEY: $('#api_handle').val() + '.' + $('#api_key').val()
            },
        });

        api_url = $('#api_url').val()

    }

</script>
</body>
</html>





