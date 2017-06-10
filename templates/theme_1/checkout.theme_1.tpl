{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2017 at 10:05:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{include file="theme_1/_head.theme_1.tpl"}


<body>

<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">


            <div class="container">

                <h2>{t}Order number{/t} <span class="order_number">342342</span></h2>


                <div class="one_third">
                    <h5 id="_invoice_address_label" contenteditable="true">{$content._invoice_address_label}</h5>
                    <p>
                        The Business Centre </br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG</br>
                    </p>
                </div><!-- end section -->


                <div class="one_third last">
                    <h5 id="_delivery_address_label" contenteditable="true">{$content._delivery_address_label}</h5>
                    <p>
                        The Business Centre</br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG </br>                   </p>
                </div><!-- end section -->

                <div class="one_third text-right" style="padding-left:20px">
                    <h5 id="_totals_label" contenteditable="true">{$content._totals_label}</h5>


                    <table class="table">

                        <tbody>
                        <tr>
                            <td>-ABB1</td>

                            <td class="text-right">£10.00</td>
                        </tr>
                        <tr>
                            <td>HHT-04</td>

                            <td class="text-right">£6.00</td>
                        </tr>
                        <tr>
                            <td>LLX-10a</td>

                            <td class="text-right">£1.99</td>
                        </tr>
                        </tbody>
                    </table>

                </div><!-- end section -->


                <div class="clearfix margin_top10"></div>

            </div>


            <div class="container">

                <ul class="tabs3">
                    <li><a href="#example-3-tab-1" target="_self"><i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp; {t}Credit Card{/t}</a></li>
                    <li><a href="#example-3-tab-2" target="_self"><i class="fa fa-paypal" aria-hidden="true"></i>&nbsp; Paypal</a></li>
                    <li><a href="#example-3-tab-3" target="_self"><i class="fa fa-university" aria-hidden="true"></i>&nbsp; {t}Bank Transfer{/t}</a></li>
                </ul>

                <div class="tabs-content3 two">

                    <div id="example-3-tab-1" class="tabs-panel3">


                        <form action="" class="sky-form"  style="max-width: 500px;"  >
                            <header>Checkout form</header>



                            <fieldset>


                                <section>
                                    <label class="input">
                                        <input type="text" placeholder="Name on card">
                                    </label>
                                </section>

                                <div class="row">
                                    <section class="col col-10">
                                        <label class="input">
                                            <input type="text" placeholder="Card numberd">
                                        </label>
                                    </section>
                                    <section class="col col-2">
                                        <label class="input">
                                            <input type="text" maxlength="3" placeholder="CVV2">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-4">Expiration date</label>
                                    <section class="col col-5">
                                        <label class="select">
                                            <select>
                                                <option value="0" selected disabled>Month</option>
                                                <option value="1">January</option>
                                                <option value="1">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label class="input">
                                            <input type="text" maxlength="4" placeholder="Year">
                                        </label>
                                    </section>
                                </div>
                            </fieldset>

                            <footer>
                                <button type="submit" class="button">Continue</button>
                            </footer>
                        </form>




                    </div><!-- end tab 1 -->

                    <div id="example-3-tab-2" class="tabs-panel3">

                    </div><!-- end tab 2 -->

                    <div id="example-3-tab-3" class="tabs-panel3">

                        <p>After placing your order, please go to your bank and make this payment to our bank account, details below.</p>

                        <br>
                            Beneficiary: Ancient Wisdom Marketing Limited<br/>
                        Bank: <b>Natwest</b><br/>
                        Address: 72 Middlewood Road Hillsborough Sheffield S6 4PB<br/>
                        Account Number: 71336850<br/>
                        Bank Code: 60-19-43<br/>
                        Swift: NWBKGB2L<br/>
                        IBAN: GB53 NWBK 6019437 1336 850<br/>
                        </p>
                        <p>
                            Remember to state the order number in the payment reference. [286015]
                        </p>
                        <p>
                        Please note, we cannot process your order until payment arrives in our account.
                        </p>
                    </div><!-- end tab 3 -->

                    <div id="example-3-tab-4" class="tabs-panel3">
                        <img src="http://placehold.it/1170x450" alt="" class="rimg"/>
                        <br/><br/>
                        <p>Development dolor sit amet, consectetur adipiscing elit. Phasellus ac fringilla nulla, sit amet consequat eros. Pellentesque pharetra blandit commodo. Phasellus massa nisl, feugiat ac bibendum
                            et, dictum id ipsum. Quisque sit amet accumsan tortor It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged many web sites
                            still in their infanc versions have evolved over the years. the Internet tend to repeat predefined chunks as necessary, making this the first true randomised words which generator on the
                            Internet.</p><br/>
                        <p>Manu desktop you need to be sure there anythin embarrassing hidden in the middle of text in the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this
                            the first true generator on the Internet.</p>

                    </div><!-- end tab 4 -->

                </div><!-- end all tabs -->


                <div class="clearfix divider_line10"></div>


            </div>


            <div class="clearfix marb12"></div>

        </div>
    </div>

    <script>

        $('[contenteditable=true]').on('input paste', function (event) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });


        function save() {

            if (!$('#save_button', window.parent.document).hasClass('save')) {
                return;
            }

            $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


            content_data = {}

                $('[contenteditable=true]').each(function (i, obj) {


                    content_data[$(obj).attr('id')] = $(obj).html()
                })

            $('.show_div').each(function (i, obj) {
                content_data[$(obj).attr('id')] = ($(obj).hasClass('hide') ? false : true)
            })


            var request = '/ar_edit_website.php?tipo=save_webpage_content&key={$webpage->id}&content_data=' + encodeURIComponent(Base64.encode(JSON.stringify(content_data)));


            console.log(request)


            $.getJSON(request, function (data) {


                $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

            })


        }


        var droppedFiles = false;

        $('#file_upload').on('change', function (e) {


            var ajaxData = new FormData();

            //var ajaxData = new FormData( );
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    ajaxData.append('files', file);
                });
            }


            $.each($('#file_upload').prop("files"), function (i, file) {
                ajaxData.append("files[" + i + "]", file);

            });


            ajaxData.append("tipo", 'upload_images')
            ajaxData.append("parent", 'webpage')
            ajaxData.append("parent_key", '{$webpage->id}')

            ajaxData.append("options", JSON.stringify({
                max_width: 350

            }))

            ajaxData.append("response_type", 'webpage')


            //   var image = $('#' + $('#image_edit_toolbar').attr('block') + ' img')


            $.ajax({
                url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


                complete: function () {

                }, success: function (data) {

                    console.log(data)

                    if (data.state == '200') {

                        console.log(data)

                        $('#_thanks_image').attr('src', data.image_src).attr('image_key', data.img_key)


                    } else if (data.state == '400') {

                    }


                }, error: function () {

                }
            });


        });


        function change_webpage_element_visibility(id, value) {


            if (value == 'hide') {
                $('#' + id).addClass('hide')
            } else {
                $('#' + id).removeClass('hide')
            }
            $('#save_button', window.parent.document).addClass('save button changed valid')


        }


    </script>

    <script src="/theme_1/tabs/assets/js/responsive-tabs.min.js" type="text/javascript"></script>


</body>

</html>


