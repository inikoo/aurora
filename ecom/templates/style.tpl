   <style>

   
     .hide{
         display:none}

     @font-face {
         font-family: 'Ubuntu';
         font-style: normal;
         font-weight: 300;
         src: local('Ubuntu Light'), local('Ubuntu-Light'), url("/fonts/ubuntu300.woff2") format('woff2');
     }

     @font-face {
         font-family: 'Ubuntu';
         font-style: normal;
         font-weight: 700;
         src: local('Ubuntu Bold'), local('Ubuntu-Bold'), url("/fonts/ubuntu700.woff2") format('woff2');
     }

     p{
         margin: 1em 0px;

     }

input {
  position: relative;
  bottom: 2px;

  padding: 4px 6px;
  font-weight: normal;
  color: #555;
  vertical-align: middle;
  background-color: #fff;
  border: 1px solid #ccc;
  margin: 0px;
  font-family: inherit;
  font-size: 100%;
  line-height: normal;
  outline: none;
  -webkit-box-sizing: content-box;
  -moz-box-sizing: content-box;
  box-sizing: content-box;
  -webkit-appearance: none;
  }


h1{

    font-family: "Ubuntu",Helvetica,Arial,sans-serif;
    font-weight: 800;
    font-size:21px;
    padding:0;
    margin:17.4333px 0px;

}


    #page_content{


        font-family: "Ubuntu",Helvetica,Arial,sans-serif;
        color:rgb(85, 85, 85);
        font-size:14px;

    }




    #description_block{
        position:relative; width:935px;margin:auto;
        padding:0px;margin-top:20px;margin-bottom: 20px;

    }

    .webpage_content_header{
        position:relative;float:left}

    #webpage_content_header_image{
        width:250px;left:20px
    }

    #webpage_content_header_text{
        left:100px; width:450px

    }


    .xproduct_blocks{
        width:970px;margin:auto;
        margin-top:20px
    }

    .xproduct_showcase {
        border: 1px solid #ccc;
        background:#fff;
        padding:0px 0px 0px 0px;
        float:left;width:218px;margin-left:18px;
        height:319px
    }



     .product_blocks{
         width:970px;margin:auto;

     }

     .product_wrap,.category_wrap{
         position: relative;
         float:left;

         margin-left:18px;

     }
     .product_block{
         width:218px;
         height:318px;
         margin-bottom:20px;   float:left
     }

     .product_showcase {
         border: 1px solid #ccc;
         background:#fff;
         padding:0px 0px 0px 0px;


     }




     .product_showcase:hover{
        border:1px solid #A3C5CC;
    }






    .wrap_to_center {

        display: table-cell;
        text-align: center;
        vertical-align: middle;
        width: 218px;
        height: 160px;
        margin-bottom:10px
    }
    .wrap_to_center img {
        vertical-align: bottom;max-width:218px;max-height:160px
    }






    .product_description{
        padding-left:10px;padding-right:10px;display:block;height:51px;
    }

    .product_prices{
        padding-left:10px;padding-right:10px;display:block;height:37px;
    }


    .product_prices.log_out{
        text-align: center;font-style: italic; color:#236E4B

    }

    .product_prices .product_price{
        color:#236E4B
    }


    .more_info{
        cursor:pointer;position:absolute;width:40px;top:-1px;left:179px;z-index:1}





    .description_block{
        margin-bottom:20px;background:#fff;padding:10px 20px;border:1px solid #eee}




    .ordering.log_out div {
        float:left;width: 50%;background-color: darkorange;color:whitesmoke;text-align: center;
        }

    .ordering.log_out span {
        height:28px;
        padding:7px 20px 5px 10px;
        display:block;height:20px;cursor:pointer;
        font-weight: 800;
    }

    .ordering.log_out span.login_button{
        border-right:1px solid white;
    }


    .ordering.log_out span:hover {
        background-color: brown;
    }


    .ordering{
    }




    .order_input{
        float:left;position:relative;top:2px;border-right:none;border-left:none;height:20px;width:40px
    }



    .product_footer{
        height:28px;position:relative;top:2px;
       padding:7px 20px 3px 10px;
        display:block;height:20px;cursor:pointer;
        float:left;font-weight: 800;
    }


    .can_not_order .product_footer{
        color:#fff;
        background-color: darkgray;cursor:auto;

    }

    .can_not_order.out_of_stock .product_footer{
        color:#fff;
        background-color: darkgray;

    }

    .can_not_order.launching_soon .product_footer{
        color:#fff;
        background-color: darkseagreen;

    }


    .can_not_order .product_footer.label{
        width:154px;
    }

    .can_not_order .product_footer.reminder{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid darkgray;cursor:pointer;
    }
    
     .can_not_order .product_footer.reminder.lock{
       background-color: #f7f7f7
     }

    .can_not_order.launching_soon .product_footer.reminder{
        border-top:1px solid darkseagreen;
        color: darkseagreen;
    }




    .product_footer.favourite{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid #ccc
    }

    .product_footer.favourite i.marked{

        color: deeppink;
    }

    .product_footer.order_button{
        width:102px;
        color:#fff;
        background-color: orange;
    }

    .product_footer.order_button:hover{
        color:#fff;
        background-color: maroon;
    }

    .product_footer.order_button.ordered{
        color:#fff;
        background-color: maroon;
    }


    .product_image{
        cursor:pointer}



     .title{
         font-weight:800;font-size:120%;padding-bottom:10px;margin-left:20px
     }


    #bottom_see_also{
        margin:auto;padding:0px;margin-top:10px;width:970px;
    }



    #bottom_see_also .item{
        height:220px;width:170px;float:left;text-align:center;margin-left:20px

    }
    #bottom_see_also .item:first-of-type{
        margin-left:20px

    }

    #bottom_see_also .item  .image_container{
    border:1px solid #ccc;height:170px;width:170px;;vertical-align:middle;text-align:center;display: table-cell;

    }

    #bottom_see_also .item  .label{
        font-size:90%;margin-top:5px

    }

    #bottom_see_also  img{
        max-height:168px;max-width: 168px;overflow:hidden;}




     .product_header_text{
         padding:4px;height:30px;color:brown ;
         border:1px solid transparent;cursor:text;

     }

     .product_header_text p{
         padding:0px ; margin:0px;text-align: center;
         z-index: 0;position:relative;
     }




     .panel{
         margin-bottom:20px;
     }

     .panel .buttons{
         position:absolute;top:10px;;display:flex;width:200px;margin-left:10px;
     }



     .panel .buttons div{
         cursor: pointer;
         background-color: snow
     }

     .panel.image{
         border:none;
     }

     .panel_1x{
         height:320px;width:220px;

     }
     .panel_2x{
         height:320px;width:457px;

     }

     .panel_3x{
         height:320px;width:696px;

     }

     .panel_4x{
         width:934px;

     }
     .panel img{
         height:100%;width:100%;border:none

     }

     .text_panel_default{
         border:1px solid #ccc;

     }

     .text_panel_default.panel_1x{
         height:318px;width:218px;
     }

     .text_panel_default.panel_2x{
         height:318px;width:455px;
     }

     .text_panel_default.panel_3x{
         height:318px;width:694px;

     }

     .text_panel_default.panel_4x{
         height:318px;width:932px;
     }


     .category_wrap  .text_panel_default.panel_1x{
            height:218px;
     }

     .category_wrap   .text_panel_default.panel_2x{
            height:218px;
     }

     .category_wrap  .text_panel_default.panel_3x{
            height:218px;

     }

     .category_wrap  .text_panel_default.panel_4x{
            height:218px;
     }


     .category_wrap  .panel_1x{
            height:220px;

     }
     .category_wrap  .panel_2x{
            height:220px;

     }
     .category_wrap  .panel_3x{
            height:220px;

     }




     .text_panel_default .panel_content{
         margin:20px;
     }

      .category_block{
            width:218px;
            height:218px;
            margin-bottom:20px;
     }

   .category_showcase {
            border: 1px solid #ccc;
            background:#fff;
            padding:0px 0px 0px 0px;


     }

     .category_header_text{
            padding:8px;height:30px;margin-bottom:5px;
            border:1px solid transparent;cursor:text;padding-top:10px;padding-bottom:0px;
            font-family: "Ubuntu",Helvetica,Arial,sans-serif;
            font-weight: 800;

     }

     .category_header_text p{
            padding:0px ; margin:0px;text-align: center;
            z-index: 100;position:relative;
     }


     .page_break{

            border-top:1px solid #ccc;margin:0px 20px 10px 20px;
            height: 30px;





     }

     .page_break span.title{

            padding:0px;margin:0px;float:left;;padding-top:5px;

     }


     .page_break .sub_title{

            padding:0px;margin:0px;float:right;padding-top:5px;min-width:60px;text-align: right;

     }

     section.product_tabs{
            display: -webkit-flex;
            display: flex;
            -webkit-flex-wrap: wrap;
            flex-wrap: wrap;

            margin:0px 20px

     }

     section.product_tabs label {
            background: #eee;
            border: 1px solid #ddd;
            padding: .5em 3em;
            cursor: pointer;
            z-index: 1;
            margin-left: -1px;

     }
     section.product_tabs label:first-of-type {
            margin-left: 0;
     }
     section.product_tabs div {
            width: 100%;
            margin-top: -1px;
            padding: 1em;
            border: 1px solid #ddd;
            -webkit-order: 1;
            order: 1;
            padding-top:30px;
            padding-bottom:30px
     }
     section.product_tabs input[type=radio], section.product_tabs div {
            display: none;
     }
     section.product_tabs  input[type=radio]:checked + label {
            background: #fff;
            border-bottom: 1px solid #fff;
     }
     section.product_tabs  input[type=radio]:checked + label + div {
            display: block;
     }
     
       {$css}
       
       </style>
