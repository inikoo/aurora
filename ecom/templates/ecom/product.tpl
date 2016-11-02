{* 
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 June 2016 at 17:43:38 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<section id="sidebar-main" class="col-md-9">
    <div id="content">
        <div class="product-info">
            <div class="row">
                <div class="col-lg-7 col-sm-6 col-xs-12 image-container">
                    <div id="img-detail" class="image">
                        <span class="product-label-special label">Sale</span> <a
                                href="images/products/{$product->get('Code')}-1-600x450.jpg"
                                title="{$product->get('Name')}" class="imagezoom"> <img
                                    src="images/products/{$product->get('Code')}-1-500x375.jpg"
                                    title="{$product->get('Name')}" alt="{$product->get('Name')}" id="image"
                                    data-zoom-image="images/products/{$product->get('Code')}-1-600x450.jpg"
                                    class="product-image-zoom img-responsive"/> </a>
                    </div>
                    <div class="image-additional slide carousel vertical" id="image-additional">
                        <div id="image-additional-carousel" class="carousel-inner">
                            <div class="item">
                                <a href="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-1-600x450.jpg"
                                   title="{$product->get('Name')}" class="imagezoom"
                                   data-zoom-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-1-600x450.jpg"
                                   data-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-1-600x450.jpg">
                                    <img src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-1-500x375.jpg"
                                         style="max-width:114px" title="{$product->get('Name')}"
                                         alt="{$product->get('Name')}"
                                         data-zoom-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-1-600x450.jpg"
                                         class="product-image-zoom img-responsive"/> </a> <a
                                        href="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-15-600x450.jpg"
                                        title="{$product->get('Name')}" class="imagezoom"
                                        data-zoom-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-15-600x450.jpg"
                                        data-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-15-600x450.jpg">
                                    <img src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-15-114x86.jpg"
                                         style="max-width:114px" title="{$product->get('Name')}"
                                         alt="{$product->get('Name')}"
                                         data-zoom-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-15-600x450.jpg"
                                         class="product-image-zoom img-responsive"/> </a> <a
                                        href="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-2-600x450.jpg"
                                        title="{$product->get('Name')}" class="imagezoom"
                                        data-zoom-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-2-600x450.jpg"
                                        data-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-2-600x450.jpg">
                                    <img src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-2-114x86.jpg"
                                         style="max-width:114px" title="{$product->get('Name')}"
                                         alt="{$product->get('Name')}"
                                         data-zoom-image="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-2-600x450.jpg"
                                         class="product-image-zoom img-responsive"/> </a>
                            </div>
                        </div>

                        <!-- Controls -->
                    </div>
                    <script type="text/javascript">
                        $('#image-additional .item:first').addClass('active');
                        $('#image-additional').carousel({interval: false})
                    </script>
                </div>
                <div class="col-md-5 col-lg-5 col-sm-6 col-xs-12">
                    <h1>
                        {if $product->get('Units Per Case')!=1}{$product->get('Units Per Case')}x {/if} {$product->get('Name')}
                    </h1>
                    <div class="review">
                        <p>
                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span
                                    class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span
                                    class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span
                                    class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span
                                    class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <a
                                    href="#review-form" class="popup-with-form"
                                    onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">0 reviews</a>
                            / <a href="#review-form" class="popup-with-form"
                                 onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">Write a
                                review</a>
                        </p>
                    </div>

                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style">
                        <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a
                                class="addthis_button_tweet"></a> <a class="addthis_button_pinterest_pinit"></a> <a
                                class="addthis_counter addthis_pill_style"></a>
                    </div>
                    <script type="text/javascript"
                            src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
                    <!-- AddThis Button END -->
                    <ul class="list-unstyled description">


                        {foreach $data.summary item=item}
                            <li class="{$item.class}"><span>{t}{$item.label}{/t}:</span> {if $item.ref}<a
                                        href="">{if isset($item.value)}{$item.value}{elseif isset($item.product_value_key)}{$product->get($item.product_value_key)}{/if}</a>{else}{if isset($item.value)}{$item.value}{elseif isset($item.product_value_key)}{$product->get($item.product_value_key)}{/if}{/if}
                            </li>
                        {/foreach}

                    </ul>
                    <div class="price">
                        <ul class="list-unstyled">
                            <li><span class="price-old hide">$182.00</span><span
                                        class="price-new"> {$product->get('Price')}
                                    <small>{$product->get('Formatted Per Outer')}</small></span></li>
                            <li class="price-tax">{t}Unit Price{/t}
                                : {$product->get('Unit Price')} {$product->get('Formatted Per Unit')}</li>
                            <li class="price-tax">{t}Unit RRP{/t}
                                : {$product->get('Unit RRP')} {$product->get('Formatted Per Unit')}</li>
                        </ul>
                    </div>
                    <div id="product">
                        <div class="product-extra">
                            <div class="quantity-adder pull-left">
                                Qty
                                <div class="wrap-qty">
                                    <span class="add-up add-action">+</span>
                                    <input type="text" name="quantity" value="1" size="2" id="input-quantity"/>
                                    <span class="add-down add-action">-</span>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" value="28"/>
                            <button type="button" id="button-cart" class="button" data-loading-text="Loading..."><span
                                        class="fa fa-shopping-cart icon"></span> <span>Add to Cart</span></button>
                        </div>
                        <div class="compare-wish">
                            <span class="links"> <a class="wishlist" onclick="wishlist.addwishlist('28');">Add to Wish List</a> <a
                                        class="compare"
                                        onclick="compare.addcompare('28');">Compare this Product</a> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tabs-group">
            <div id="tabs" class="htabs clearfix">
                <ul class="nav clearfix">
                    <li class="active"><a href="#tab-description" data-toggle="tab">Description</a></li>
                    <li><a href="#tab-review" data-toggle="tab">Reviews (0)</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-description">
                    <div>
                        {$product->get('Description')}
                    </div>
                </div>
                <div class="tab-pane" id="tab-review">
                    <div id="review">
                    </div>
                    <p>
                        <a href="#review-form" class="popup-with-form btn btn-sm btn-danger"
                           onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">Write a review</a>
                    </p>
                    <div class="hide">
                        <div id="review-form" class="panel review-form-width">
                            <div class="panel-body">
                                <form class="form-horizontal" id="form-review">
                                    <h2>
                                        Write a review
                                    </h2>
                                    <div class="form-group required">
                                        <div class="col-sm-12">
                                            <label class="control-label" for="input-name">Your Name</label>
                                            <input type="text" name="name" value="" id="input-name"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <div class="col-sm-12">
                                            <label class="control-label" for="input-review">Your Review</label>
                                            <textarea name="text" rows="5" id="input-review"
                                                      class="form-control"></textarea>
                                            <div class="help-block">
                                                <span class="text-danger">Note:</span> HTML is not translated!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <div class="col-sm-12">
                                            <label class="control-label">Rating</label> &nbsp;&nbsp;&nbsp; Bad&nbsp;
                                            <input type="radio" name="rating" value="1"/>
                                            &nbsp;
                                            <input type="radio" name="rating" value="2"/>
                                            &nbsp;
                                            <input type="radio" name="rating" value="3"/>
                                            &nbsp;
                                            <input type="radio" name="rating" value="4"/>
                                            &nbsp;
                                            <input type="radio" name="rating" value="5"/>
                                            &nbsp;Good
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="g-recaptcha"
                                                 data-sitekey="6LcTyAYTAAAAAD3hKJNuJVIZbRjJRo33MbF4qF7n">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buttons">
                                        <div class="pull-right">
                                            <button type="button" id="button-review" data-loading-text="Loading..."
                                                    class="btn button btn-primary">Continue
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-related box">
            <div class="box-heading">
                <span>Related Products(7)</span>
            </div>
            <div id="related" class="slide carousel product-grid" data-interval="0">
                <div class="carousel-controls">
                    <a class="carousel-control left icon-angle-left" href="#related" data-slide="prev"></a> <a
                            class="carousel-control right icon-angle-right" href="#related" data-slide="next"></a>
                </div>
                <div class="products-block carousel-inner">
                    <div class="item active">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="product-block">
                                    <div class="image">
                                        <span class="product-label-special label">Sale</span> <a
                                                href="http://demopavothemes.com/pav_floral/image/catalog/demo/product-1.jpg"
                                                class="info-view colorbox product-zoom hidden-sm hidden-xs cboxElement"
                                                title="{$product->get('Name')}"><span class="fa fa-search-plus"></span></a>
                                        <a class="img"
                                           href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=28"><img
                                                    src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-1-300x225.jpg"
                                                    alt="{$product->get('Name')}" class="img-responsive"/></a>
                                        <div class="quickview hidden-sm hidden-xs">
                                            <a class="iframe-link pav-colorbox"
                                               href="http://demopavothemes.com/pav_floral/index.php?route=themecontrol/product&amp;product_id=28"
                                               title="Quick View"><span class="fa fa-eye"></span>Quick View</a>
                                        </div>
                                        <div class="img-overlay">
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="name">
                                            <a href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=28">{$product->get('Name')}</a>
                                        </div>
                                        <div class="rating">
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                        </div>
                                        <div class="description">
                                            Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis
                                            bibendum auctor, nisi e.....
                                        </div>
                                        <div class="group-item">
                                            <div class="price" itemtype="http://schema.org/Offer" itemscope>
                                                <span class="price-old">$182.00</span> <span
                                                        class="price-new">$14.00</span>
                                                <meta content="14.00" itemprop="price">
                                                <meta content="" itemprop="priceCurrency">
                                            </div>
                                            <div class="cart">
                                                <button class="button" type="button" onclick="cart.addcart('28');"><span
                                                            class="fa fa-shopping-cart icon"></span>
                                                    <span>Add to Cart</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wishlist-compare">
                                        <a class="wishlist" onclick="wishlist.addwishlist('28');"
                                           title="Add to Wish List" data-placement="top" data-toggle="tooltip"
                                           data-original-title="Add to Wish List">Add to Wish List </a> <a
                                                class="compare" onclick="compare.addcompare('28');"
                                                title="Compare this Product" data-placement="top" data-toggle="tooltip"
                                                data-original-title="Compare this Product">Compare this Product </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="product-block">
                                    <div class="image">
                                        <span class="product-label-special label">Sale</span> <a
                                                href="http://demopavothemes.com/pav_floral/image/catalog/demo/product-10.jpg"
                                                class="info-view colorbox product-zoom hidden-sm hidden-xs cboxElement"
                                                title="Chair"><span class="fa fa-search-plus"></span></a> <a class="img"
                                                                                                             href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=40"><img
                                                    src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-10-300x225.jpg"
                                                    alt="Chair" class="img-responsive"/></a>
                                        <div class="quickview hidden-sm hidden-xs">
                                            <a class="iframe-link pav-colorbox"
                                               href="http://demopavothemes.com/pav_floral/index.php?route=themecontrol/product&amp;product_id=40"
                                               title="Quick View"><span class="fa fa-eye"></span>Quick View</a>
                                        </div>
                                        <div class="img-overlay">
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="name">
                                            <a href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=40">Chair</a>
                                        </div>
                                        <div class="rating">
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                        </div>
                                        <div class="description">
                                            Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis
                                            bibendum auctor, nisi e.....
                                        </div>
                                        <div class="group-item">
                                            <div class="price" itemtype="http://schema.org/Offer" itemscope>
                                                <span class="price-old">$123.20</span> <span
                                                        class="price-new">$20.00</span>
                                                <meta content="20.00" itemprop="price">
                                                <meta content="" itemprop="priceCurrency">
                                            </div>
                                            <div class="cart">
                                                <button class="button" type="button" onclick="cart.addcart('40');"><span
                                                            class="fa fa-shopping-cart icon"></span>
                                                    <span>Add to Cart</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wishlist-compare">
                                        <a class="wishlist" onclick="wishlist.addwishlist('40');"
                                           title="Add to Wish List" data-placement="top" data-toggle="tooltip"
                                           data-original-title="Add to Wish List">Add to Wish List </a> <a
                                                class="compare" onclick="compare.addcompare('40');"
                                                title="Compare this Product" data-placement="top" data-toggle="tooltip"
                                                data-original-title="Compare this Product">Compare this Product </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="product-block">
                                    <div class="image">
                                        <span class="product-label-special label">Sale</span> <a
                                                href="http://demopavothemes.com/pav_floral/image/catalog/demo/product-14.jpg"
                                                class="info-view colorbox product-zoom hidden-sm hidden-xs cboxElement"
                                                title="iPod Shuffle"><span class="fa fa-search-plus"></span></a> <a
                                                class="img"
                                                href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=41"><img
                                                    src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-14-300x225.jpg"
                                                    alt="iPod Shuffle" class="img-responsive"/></a>
                                        <div class="quickview hidden-sm hidden-xs">
                                            <a class="iframe-link pav-colorbox"
                                               href="http://demopavothemes.com/pav_floral/index.php?route=themecontrol/product&amp;product_id=41"
                                               title="Quick View"><span class="fa fa-eye"></span>Quick View</a>
                                        </div>
                                        <div class="img-overlay">
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="name">
                                            <a href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=41">iPod
                                                Shuffle</a>
                                        </div>
                                        <div class="rating">
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                        </div>
                                        <div class="description">
                                            Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis
                                            bibendum auctor, nisi e.....
                                        </div>
                                        <div class="group-item">
                                            <div class="price" itemtype="http://schema.org/Offer" itemscope>
                                                <span class="price-old">$121.86</span> <span
                                                        class="price-new">$6,002.00</span>
                                                <meta content="6,002" itemprop="price">
                                                <meta content="" itemprop="priceCurrency">
                                            </div>
                                            <div class="cart">
                                                <button class="button" type="button" onclick="cart.addcart('41');"><span
                                                            class="fa fa-shopping-cart icon"></span>
                                                    <span>Add to Cart</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wishlist-compare">
                                        <a class="wishlist" onclick="wishlist.addwishlist('41');"
                                           title="Add to Wish List" data-placement="top" data-toggle="tooltip"
                                           data-original-title="Add to Wish List">Add to Wish List </a> <a
                                                class="compare" onclick="compare.addcompare('41');"
                                                title="Compare this Product" data-placement="top" data-toggle="tooltip"
                                                data-original-title="Compare this Product">Compare this Product </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="product-block">
                                    <div class="image">
                                        <span class="product-label-special label">Sale</span> <a
                                                href="http://demopavothemes.com/pav_floral/image/catalog/demo/product-15.jpg"
                                                class="info-view colorbox product-zoom hidden-sm hidden-xs cboxElement"
                                                title="Couch"><span class="fa fa-search-plus"></span></a> <a class="img"
                                                                                                             href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=42"><img
                                                    src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-15-300x225.jpg"
                                                    alt="Couch" class="img-responsive"/></a>
                                        <div class="quickview hidden-sm hidden-xs">
                                            <a class="iframe-link pav-colorbox"
                                               href="http://demopavothemes.com/pav_floral/index.php?route=themecontrol/product&amp;product_id=42"
                                               title="Quick View"><span class="fa fa-eye"></span>Quick View</a>
                                        </div>
                                        <div class="img-overlay">
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="name">
                                            <a href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=42">Couch</a>
                                        </div>
                                        <div class="rating">
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                        </div>
                                        <div class="description">
                                            Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis
                                            bibendum auctor, nisi e.....
                                        </div>
                                        <div class="group-item">
                                            <div class="price" itemtype="http://schema.org/Offer" itemscope>
                                                <span class="price-old">$92.00</span> <span
                                                        class="price-new">$110.00</span>
                                                <meta content="110.00" itemprop="price">
                                                <meta content="" itemprop="priceCurrency">
                                            </div>
                                            <div class="cart">
                                                <button class="button" type="button" onclick="cart.addcart('42');"><span
                                                            class="fa fa-shopping-cart icon"></span>
                                                    <span>Add to Cart</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wishlist-compare">
                                        <a class="wishlist" onclick="wishlist.addwishlist('42');"
                                           title="Add to Wish List" data-placement="top" data-toggle="tooltip"
                                           data-original-title="Add to Wish List">Add to Wish List </a> <a
                                                class="compare" onclick="compare.addcompare('42');"
                                                title="Compare this Product" data-placement="top" data-toggle="tooltip"
                                                data-original-title="Compare this Product">Compare this Product </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item ">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="product-block">
                                    <div class="image">
                                        <a href="http://demopavothemes.com/pav_floral/image/catalog/demo/product-16.jpg"
                                           class="info-view colorbox product-zoom hidden-sm hidden-xs cboxElement"
                                           title="Destroyed tee"><span class="fa fa-search-plus"></span></a> <a
                                                class="img"
                                                href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=43"><img
                                                    src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-16-300x225.jpg"
                                                    alt="Destroyed tee" class="img-responsive"/></a>
                                        <div class="quickview hidden-sm hidden-xs">
                                            <a class="iframe-link pav-colorbox"
                                               href="http://demopavothemes.com/pav_floral/index.php?route=themecontrol/product&amp;product_id=43"
                                               title="Quick View"><span class="fa fa-eye"></span>Quick View</a>
                                        </div>
                                        <div class="img-overlay">
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="name">
                                            <a href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=43">Destroyed
                                                tee</a>
                                        </div>
                                        <div class="rating">
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                        </div>
                                        <div class="description">
                                            Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis
                                            bibendum auctor, nisi e.....
                                        </div>
                                        <div class="group-item">
                                            <div class="price" itemtype="http://schema.org/Offer" itemscope>
                                                <span class="special-price">$602.00</span>
                                                <meta content="602.00" itemprop="price">
                                                <meta content="" itemprop="priceCurrency">
                                            </div>
                                            <div class="cart">
                                                <button class="button" type="button" onclick="cart.addcart('43');"><span
                                                            class="fa fa-shopping-cart icon"></span>
                                                    <span>Add to Cart</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wishlist-compare">
                                        <a class="wishlist" onclick="wishlist.addwishlist('43');"
                                           title="Add to Wish List" data-placement="top" data-toggle="tooltip"
                                           data-original-title="Add to Wish List">Add to Wish List </a> <a
                                                class="compare" onclick="compare.addcompare('43');"
                                                title="Compare this Product" data-placement="top" data-toggle="tooltip"
                                                data-original-title="Compare this Product">Compare this Product </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="product-block">
                                    <div class="image">
                                        <a href="http://demopavothemes.com/pav_floral/image/catalog/demo/product-17.jpg"
                                           class="info-view colorbox product-zoom hidden-sm hidden-xs cboxElement"
                                           title="Vintage sofas"><span class="fa fa-search-plus"></span></a> <a
                                                class="img"
                                                href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=44"><img
                                                    src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-17-300x225.jpg"
                                                    alt="Vintage sofas" class="img-responsive"/></a>
                                        <div class="quickview hidden-sm hidden-xs">
                                            <a class="iframe-link pav-colorbox"
                                               href="http://demopavothemes.com/pav_floral/index.php?route=themecontrol/product&amp;product_id=44"
                                               title="Quick View"><span class="fa fa-eye"></span>Quick View</a>
                                        </div>
                                        <div class="img-overlay">
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="name">
                                            <a href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=44">Vintage
                                                sofas</a>
                                        </div>
                                        <div class="rating">
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                        </div>
                                        <div class="description">
                                            Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis
                                            bibendum auctor, nisi e.....
                                        </div>
                                        <div class="group-item">
                                            <div class="price" itemtype="http://schema.org/Offer" itemscope>
                                                <span class="special-price">$1,202.00</span>
                                                <meta content="1,202" itemprop="price">
                                                <meta content="" itemprop="priceCurrency">
                                            </div>
                                            <div class="cart">
                                                <button class="button" type="button" onclick="cart.addcart('44');"><span
                                                            class="fa fa-shopping-cart icon"></span>
                                                    <span>Add to Cart</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wishlist-compare">
                                        <a class="wishlist" onclick="wishlist.addwishlist('44');"
                                           title="Add to Wish List" data-placement="top" data-toggle="tooltip"
                                           data-original-title="Add to Wish List">Add to Wish List </a> <a
                                                class="compare" onclick="compare.addcompare('44');"
                                                title="Compare this Product" data-placement="top" data-toggle="tooltip"
                                                data-original-title="Compare this Product">Compare this Product </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <div class="product-block">
                                    <div class="image">
                                        <span class="product-label-special label">Sale</span> <a
                                                href="http://demopavothemes.com/pav_floral/image/catalog/demo/product-10.jpg"
                                                class="info-view colorbox product-zoom hidden-sm hidden-xs cboxElement"
                                                title="T-Shirt"><span class="fa fa-search-plus"></span></a> <a
                                                class="img"
                                                href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=48"><img
                                                    src="http://demopavothemes.com/pav_floral/image/cache/catalog/demo/product-10-300x225.jpg"
                                                    alt="T-Shirt" class="img-responsive"/></a>
                                        <div class="quickview hidden-sm hidden-xs">
                                            <a class="iframe-link pav-colorbox"
                                               href="http://demopavothemes.com/pav_floral/index.php?route=themecontrol/product&amp;product_id=48"
                                               title="Quick View"><span class="fa fa-eye"></span>Quick View</a>
                                        </div>
                                        <div class="img-overlay">
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="name">
                                            <a href="http://demopavothemes.com/pav_floral/index.php?route=product/product&amp;product_id=48">T-Shirt</a>
                                        </div>
                                        <div class="rating">
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                        </div>
                                        <div class="description">
                                            Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis
                                            bibendum auctor, nisi e.....
                                        </div>
                                        <div class="group-item">
                                            <div class="price" itemtype="http://schema.org/Offer" itemscope>
                                                <span class="price-old">$122.00</span> <span
                                                        class="price-new">$56.00</span>
                                                <meta content="56.00" itemprop="price">
                                                <meta content="" itemprop="priceCurrency">
                                            </div>
                                            <div class="cart">
                                                <button class="button" type="button" onclick="cart.addcart('48');"><span
                                                            class="fa fa-shopping-cart icon"></span>
                                                    <span>Add to Cart</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wishlist-compare">
                                        <a class="wishlist" onclick="wishlist.addwishlist('48');"
                                           title="Add to Wish List" data-placement="top" data-toggle="tooltip"
                                           data-original-title="Add to Wish List">Add to Wish List </a> <a
                                                class="compare" onclick="compare.addcompare('48');"
                                                title="Compare this Product" data-placement="top" data-toggle="tooltip"
                                                data-original-title="Compare this Product">Compare this Product </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
