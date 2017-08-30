{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 00:07:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>
<div id="page-transitions">
    <div class="sidebars sidebars-light">
        <div class="sidebar sidebar-left">
            <div class="sidebar-header sidebar-header-image bg-2">
                <div class="overlay dark-overlay"></div>
                <div class="sidebar-socials">
                    <a href="#"><i class="ion-social-facebook"></i></a>
                    <a href="#"><i class="ion-social-twitter"></i></a>
                    <a href="#"><i class="ion-ios-telephone"></i></a>
                    <a href="#"><i class="ion-android-mail"></i></a>
                    <a class="close-sidebar" href="#"><i class="ion-android-close"></i></a>
                    <div class="clear"></div>
                </div>
                <a href="index.html" class="sidebar-logo">
                    <strong>The Ultimate Mobile Solution</strong>
                </a>
            </div>
            <div class="menu-search">
                <i class="ion-ios-search-strong"></i>
                <input type="text" class="search-field" value="Search..." onblur="if (this.value == '') {
                this.value = 'Search...';}" onfocus="if (this.value == 'Search...') {
                this.value = '';}" >
            </div>
            <div class="menu-options icon-background no-submenu-numbers sidebar-menu">
                <em class="menu-divider">Navigation</em>
                <a href="index.html"><i class="icon-bg bg-night-dark ion-ios-star"></i><span>Welcome</span><i class="ion-record"></i></a>
                <a data-sub="sidebar-sub-1" href="#"><i class="icon-bg bg-blue-dark ion-android-home"></i><span>Homepages</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-1">
                    <a href="index.html"><span>Homepage</span></a>
                    <a href="index2.html"><span>Homepage 2</span></a>
                    <a href="index-cover.html"><span>Cover Slider</span></a>
                    <a href="index-circle.html"><span>Circle Slider</span></a>
                    <a href="index-no-slider.html"><span>No Slider</span></a>
                    <a href="index-store.html"><span>Store Front</span></a>
                    <a href="index-news.html"><span>News Front</span></a>
                    <a href="index-splash.html"><span>Splash</span></a>
                    <a href="index-login.html"><span>Login</span></a>
                    <a href="index-register.html"><span>Register</span></a>
                    <a href="index-landing.html"><span>Landing Page</span></a>
                </div>
                <a data-sub="sidebar-sub-0" href="#"><i class="icon-bg bg-red-dark ion-navicon"></i><span>Menus</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-0">
                    <a href="menu-sidebar-dual.html"><span>Dual Sidebar</span></a>
                    <a href="menu-sidebar-left.html"><span>Left Sidebar</span></a>
                    <a href="menu-sidebar-right.html"><span>Right Sidebar</span></a>
                    <a href="menu-modal.html"><span>Modal Menu</span></a>
                    <a href="menu-header.html"><span>Header Menu</span></a>
                    <a href="menu-footer.html"><span>Footer Menu</span></a>
                    <a href="menu-fixed.html"><span>Footer Fixed</span></a>
                    <a href="menu-floating.html"><span>Floating Icons</span></a>
                    <a href="menu-landing.html"><span>Landing Menu</span></a>
                    <a class="toggle-menu-style" href="#"><span>Toggle Menu Icons</span></a>
                    <a class="toggle-menu-color" href="#"><span>Toggle Menu Colors</span></a>
                    <a class="toggle-menu-numbers" href="#"><span>Toggle Menu Numbers</span></a>
                </div>
                <a data-sub="sidebar-sub-2" href="#"><i class="icon-bg bg-green-dark ion-gear-a"></i><span>Features</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-2">
                    <a href="features-tabs.html"><span>Tabs</span></a>
                    <a href="features-toggles.html"><span>Toggles</span></a>
                    <a href="features-dropdown.html"><span>Dropdowns</span></a>
                    <a href="features-accordion.html"><span>Accordions</span></a>
                    <a href="features-typography.html"><span>Typography</span></a>
                    <a href="features-menu-bars.html"><span>Menu Bars</span></a>
                    <a href="features-notifications.html"><span>Notifications</span></a>
                    <a href="features-decorations.html"><span>Deco & Divicers</span></a>
                    <a href="features-buttons.html"><span>Buttons & Icons</span></a>
                    <a href="features-inputs.html"><span>Input Elements</span></a>
                    <a href="features-detection.html"><span>Device Detection</span></a>
                </div>
                <a data-sub="sidebar-sub-3" href="#"><i class="icon-bg bg-blue-dark ion-ios-analytics"></i><span>Galleries</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-3">
                    <a href="gallery-round.html"><span>Round</span></a>
                    <a href="gallery-square.html"><span>Square</span></a>
                    <a href="gallery-blocks.html"><span>Blocks</span></a>
                    <a href="gallery-adaptive.html"><span>Adaptive</span></a>
                    <a href="gallery-collections.html"><span>Collections</span></a>
                </div>
                <a data-sub="sidebar-sub-4" href="#"><i class="icon-bg bg-magenta-dark ion-image"></i><span>Portfolio</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-4">
                    <a href="portfolio-one.html"><span>One</span></a>
                    <a href="portfolio-two.html"><span>Two</span></a>
                    <a href="portfolio-fluid.html"><span>Fluid</span></a>
                    <a href="portfolio-cards.html"><span>Cards</span></a>
                    <a href="portfolio-adaptive.html"><span>Adaptive</span></a>
                    <a href="portfolio-widescreen.html"><span>Widescreen</span></a>
                </div>
                <a data-sub="sidebar-sub-5" href="#"><i class="icon-bg bg-orange-dark ion-ios-list-outline"></i><span>Site Pages</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-5">
                    <a href="page-error.html"><span>Error</span></a>
                    <a href="page-soon.htm"><span>Soon</span></a>
                    <a href="page-login.html"><span>Login</span></a>
                    <a href="page-register.html"><span>Register</span></a>
                    <a href="page-reviews.html"><span>Reviews</span></a>
                    <a href="page-sitemap.html"><span>Sitemap</span></a>
                    <a href="page-profile-1.html"><span>Profile 1</span></a>
                    <a href="page-profile-2.html"><span>Profile 2</span></a>
                    <a href="page-timeline-1.html"><span>Timeline 1</span></a>
                    <a href="page-timeline-2.html"><span>Timeline 2</span></a>
                    <a href="page-pricing.html"><span>Pricing Table</span></a>
                    <a href="page-status.html"><span>System Status</span></a>
                </div>
                <a data-sub="sidebar-sub-6" href="#"><i class="icon-bg bg-night-dark ion-heart"></i><span>AppStyled</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-6">
                    <a href="pageapp-chat.html"><span>Chat</span></a>
                    <a href="pageapp-error.html"><span>Error</span></a>
                    <a href="pageapp-soon.html"><span>Soon</span></a>
                    <a href="pageapp-login.html"><span>Login</span></a>
                    <a href="pageapp-register.html"><span>Register</span></a>
                    <a href="pageapp-tasklist.html"><span>Tasklist</span></a>
                    <a href="pageapp-checklist.html"><span>Checklist</span></a>
                    <a href="pageapp-interests.html"><span>Interests</span></a>
                    <a href="pageapp-calendar.html"><span>Calendar</span></a>
                    <a href="pageapp-coverpage.html"><span>Coverpage</span></a>
                    <a href="pageapp-userlists.html"><span>User Lists</span></a>
                    <a href="pageapp-map.html"><span>Fullscreen Map</span></a>
                </div>
                <a data-sub="sidebar-sub-7" href="#"><i class="icon-bg bg-green-dark ion-ios-paper-outline"></i><span>News</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-7">
                    <a href="news-faq.html"><span>FAQ</span></a>
                    <a href="news-home-1.html"><span>Home 1</span></a>
                    <a href="news-home-2.html"><span>Home 2</span></a>
                    <a href="news-cover.html"><span>Cover</span></a>
                    <a href="news-article-1.html"><span>Article 1</span></a>
                    <a href="news-article-2.html"><span>Article 2</span></a>
                    <a href="news-article-3.html"><span>Article 3</span></a>
                    <a href="news-list-1.html"><span>Article List 1</span></a>
                    <a href="news-list-2.html"><span>Article List 2</span></a>
                    <a href="news-list-3.html"><span>Article List 3</span></a>
                    <a href="news-archive.html"><span>Article Archive</span></a>
                </div>
                <a class="active-item" data-sub="sidebar-sub-8" href="#"><i class="icon-bg bg-blue-dark ion-ios-cart"></i><span>Store</span><strong class="plushide-animated"></strong></a>
                <div class="submenu" id="sidebar-sub-8">
                    <a href="store-faq.html"><span>FAQ</span></a>
                    <a href="store-home-1.html"><span>Homepage 1</span></a>
                    <a href="store-home-2.html"><span>Homepage 2</span></a>
                    <a href="store-apps.html"><span>Home Apps</span></a>
                    <a href="store-music.html"><span>Home Music</span></a>
                    <a href="store-video.html"><span>Home Video</span></a>
                    <a href="store-cover.html"><span>Home Cover</span></a>
                    <a href="store-product-1.html"><span>Product 1</span></a>
                    <a class="active-item" href="store-product-2.html"><span>Product 2</span></a>
                    <a href="store-cart-1.html"><span>Cart 1</span></a>
                    <a href="store-cart-2.html"><span>Cart 2</span></a>
                    <a href="store-cart-3.html"><span>Cart 3</span></a>
                    <a href="store-invoice.html"><span>Invoice</span></a>
                    <a href="store-history.html"><span>History</span></a>
                    <a href="store-locations.html"><span>Locations</span></a>
                    <a href="store-checkout.html"><span>Checkout</span></a>
                </div>
                <a href="page-blog.html"><i class="icon-bg bg-orange-light ion-edit"></i><span>Blog</span><i class="ion-record"></i></a>
                <a href="page-videos.html"><i class="icon-bg bg-pink-dark ion-ios-film-outline"></i><span>Videos</span><i class="ion-record"></i></a>
                <a href="page-contact.html"><i class="icon-bg bg-green-dark ion-ios-chatboxes-outline"></i><span>Contact</span><i class="ion-record"></i></a>
                <a href="#" class="close-sidebar"><i class="icon-bg bg-red-light ion-android-close"></i><span>Close</span><i class="ion-record"></i></a>
                <em class="menu-divider">Copyright <u class="copyright-year"></u>. All rights reserved</em>
            </div>
        </div>
        <div class="sidebar sidebar-right">
            <div class="sidebar-header sidebar-header-classic">
                <div class="sidebar-socials">
                    <a class="close-sidebar" href="#"><i class="ion-android-close"></i></a>
                    <a href="#"><i class="ion-social-facebook"></i></a>
                    <a href="#"><i class="ion-social-twitter"></i></a>
                    <a href="#"><i class="ion-ios-telephone"></i></a>
                    <a href="#"><i class="ion-android-mail"></i></a>
                    <div class="clear"></div>
                </div>
                <a href="index.html" class="sidebar-logo">
                    <strong>The Ultimate Mobile Solution</strong>
                </a>
            </div>

            <div class="menu-options icon-background sidebar-menu">
                <em class="menu-divider">Social Networks</em>
                <a class="default-link" href="https://www.facebook.com/enabled.labs/"><i class="icon-bg facebook-bg ion-social-facebook"></i><span>Facebook</span><i class="ion-record"></i></a>
                <a class="default-link" href="https://twitter.com/iEnabled"><i class="icon-bg twitter-bg ion-social-twitter"></i><span>Twitter</span><i class="ion-record"></i></a>
                <a class="default-link" href="https://plus.google.com/u/1/105775801838187143320"><i class="icon-bg google-bg ion-social-googleplus"></i><span>Google +</span><i class="ion-record"></i></a>
                <em class="menu-divider">Get in touch with us</em>
                <a href="contact.html"><i class="icon-bg mail-bg ion-email"></i><span>Email</span><i class="ion-record"></i></a>
                <a href="tel:+1 234 567 890"><i class="icon-bg phone-bg ion-ios-telephone"></i><span>Phone</span><i class="ion-record"></i></a>
                <a href="https://api.whatsapp.com/send?phone=12345678900"><i class="icon-bg whatsapp-bg ion-social-whatsapp"></i><span>Whatsapp</span><i class="ion-record"></i></a>
                <em class="menu-divider">NEWS AND SOCIAL FEED</em>
                <a class="default-link" href="https://www.pinterest.com/enableds/pins/"><i class="icon-bg pinterest-bg ion-social-pinterest-outline"></i><span>Pinterest</span><i class="ion-record"></i></a>
                <a class="default-link" href="https://www.youtube.com/user/Envato"><i class="icon-bg youtube-bg ion-social-youtube"></i><span>YouTube</span><i class="ion-record"></i></a>
                <em class="menu-divider">Copyright <u class="copyright-year"></u>. All rights reserved</em>
            </div>
        </div>
    </div>

    <div class="header header-logo-center header-light">
        <a href="#" class="header-icon header-icon-1 hamburger-animated open-sidebar-left"></a>
        <a href="index.html" class="header-logo"></a>
        <a href="#" class="header-icon header-icon-4 open-sidebar-right"><i class="ion-ios-email-outline"></i></a>
    </div>

    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->

            <div class="content-fullscren">
                <div class="store-slider no-bottom">
                    <div class="swiper-wrapper">
                        <a href="#" class="swiper-slide store-slider-item">
                            <img class="responsive-image no-bottom" src="images/pictures/1.jpg" alt="img">
                        </a>
                        <a href="#" class="swiper-slide store-slider-item">
                            <img class="responsive-image no-bottom" src="images/pictures/2.jpg" alt="img">
                        </a>
                        <a href="#" class="swiper-slide store-slider-item">
                            <img class="responsive-image no-bottom" src="images/pictures/3.jpg" alt="img">
                        </a>
                    </div>
                </div>

                <div class="decoration-lines container-fullscreen">
                    <div class="deco-0"></div>
                    <div class="deco-1"></div>
                    <div class="deco-2"></div>
                    <div class="deco-3"></div>
                    <div class="deco-4"></div>
                    <div class="deco-5"></div>
                    <div class="deco-6"></div>
                    <div class="deco-7"></div>
                    <div class="deco-8"></div>
                    <div class="deco-9"></div>
                </div>
            </div>

            <div class="content">
                <div class="store-product-header">
                    <h2 class="center-text">ProMobile</h2>
                    <p class="boxed-text center-text">
                        All the content of our Bonus Pages can be added here to describe your product as you wish and as you need.
                    </p>

                    <div class="store-product-socials full-bottom">
                        <a class="bg-blue-dark scale-hover" href="#"><i class="ion-ios-cart"></i></a>
                        <a class="bg-red-dark scale-hover" href="#"><i class="ion-heart"></i></a>
                        <a class="bg-green-dark scale-hover" href="#"><i class="ion-bookmark"></i></a>
                        <a class="bg-orange-dark scale-hover show-share-bottom" href="#"><i class="ion-android-refresh"></i></a>
                        <div class="clear"></div>
                    </div>

                    <div class="decoration half-bottom full-top"></div>
                    <div class="store-product-rating half-top">
                        <h1>4.5</h1>
                        <div>
                            <em><i class="ion-android-star"></i></em>
                            <em><i class="ion-android-star"></i></em>
                            <em><i class="ion-android-star"></i></em>
                            <em><i class="ion-android-star"></i></em>
                            <em><i class="ion-android-star-half"></i></em>
                        </div>
                        <strong>181 Reviews</strong>
                    </div>
                    <div class="store-product-icons">
                        <strong><i class="ion-card"></i></strong>
                        <strong><i class="ion-cash"></i></strong>
                        <strong><i class="ion-android-car"></i></strong>
                    </div>
                    <div class="decoration half-top"></div>

                    <p class="dropcaps-1">
                        Aorem ipsum dolor sit amet, consectetur adipiscing elit. Donec varius dui et erat aliquet rutrum. Nunc vitae tincidunt magna. In vitae efficitur enim. Fusce a augue mi. Vivamus a nunc in ante semper iaculis
                    </p>
                    <div class="clear"></div>

                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec lectus vitae
                        metus vehicula cursus. Donec purus ex, ultricies vel iaculis sed, consectetur
                        venenatis sapien. Quisque auctor justo libero, vel aliquam odio consequat quis.
                        Nunc accumsan efficitur felis eu egestas.
                    </p>

                    <div class="decoration"></div>

                    <div class="container">
                        <div class="one-half">
                            <a href="#" class="button button-icon button-blue button-round button-full button-xs no-bottom"><i class="ion-social-usd"></i>Purchase</a>
                        </div>
                        <div class="one-half last-column">
                            <a href="#" class="button button-icon button-green button-round button-full button-xs no-bottom"><i class="ion-android-bookmark"></i>Wishlist</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="decoration"></div>

                    <div class="one-half">
                        <h5>1/2</h5>
                        <img class="preload-image responsive-image half-bottom" data-original="images/pictures/1w.jpg" src="images/empty.png" alt="img">
                        <p>
                            This column is half the size of the screen, and will stay this way on mobile and tablets.
                        </p>
                    </div>
                    <div class="one-half last-column">
                        <h5>2/2</h5>
                        <img class="preload-image responsive-image half-bottom" data-original="images/pictures/2w.jpg" src="images/empty.png" alt="img">
                        <p>
                            This column is half the size of the screen, and will stay this way on mobile and tablets.
                        </p>
                    </div>
                    <div class="clear"></div>

                    <div class="decoration"></div>

                    <div class="review-4 container">
                        <h1>5.00</h1>
                        <h2>John Doe said:</h2>
                        <div class="review-stars">
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                        </div>
                        <img data-original="images/pictures/1t.jpg" alt="img" class="preload-image" src="images/empty.png">
                        <p>
                            The best support I have ever had. They are on top of things and with very fast
                            and accurate replies. I will continue to purchase items as needed and will gladly
                            recommend there service to anyone. I would give them 10 stars if I could. Keep up
                            the great work and even better service.
                        </p>
                        <a href="#">By Enabled on <u>ThemeForest Item</u></a>
                    </div>

                    <div class="review-4 container">
                        <h1>4.00</h1>
                        <h2>John Doe said:</h2>
                        <div class="review-stars">
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                        </div>
                        <img data-original="images/pictures/2t.jpg" alt="img" class="preload-image" src="images/empty.png">
                        <p>
                            The best support I have ever had. They are on top of things and with very fast
                            and accurate replies. I will continue to purchase items as needed and will gladly
                            recommend there service to anyone. I would give them 10 stars if I could. Keep up
                            the great work and even better service.
                        </p>
                        <a href="#">By Enabled on <u>ThemeForest Item</u></a>
                    </div>

                    <div class="review-4 container">
                        <h1>5.00</h1>
                        <h2>John Doe said:</h2>
                        <div class="review-stars">
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                            <i class="ion-android-star"></i>
                        </div>
                        <img data-original="images/pictures/3t.jpg" alt="img" class="preload-image" src="images/empty.png">
                        <p>
                            The best support I have ever had. They are on top of things and with very fast
                            and accurate replies. I will continue to purchase items as needed and will gladly
                            recommend there service to anyone. I would give them 10 stars if I could. Keep up
                            the great work and even better service.
                        </p>
                        <a href="#">By Enabled on <u>ThemeForest Item</u></a>
                    </div>

                    <div class="decoration"></div>

                    <div class="container heading-style">
                        <h4 class="heading-title">Other related products!</h4>
                        <i class="ion-android-star-outline heading-icon"></i>
                        <div class="line bg-black"></div>
                        <p class="heading-subtitle">
                            More products you'd love to see from this cateogory, or who knows what
                            other awesome stuff you'll add here
                        </p>
                    </div>

                </div>
            </div>

            <div class="content-fullscreen half-bottom">
                <div class="category-slider">
                    <div class="swiper-wrapper">
                        <a class="swiper-slide" href="#">
                            <img class="responsive-image" src="images/pictures/3t.jpg" alt="img">
                            <em>Bread and Butter</em>
                            <strong>$55.30</strong>
                            <u class="color-green-dark">Free Shipping</u>
                        </a>
                        <a class="swiper-slide" href="#">
                            <img class="responsive-image" src="images/pictures/2t.jpg" alt="img">
                            <em>Frying Pans</em>
                            <strong>$55.30</strong>
                            <u class="color-blue-dark">5% Discount</u>
                        </a>
                        <a class="swiper-slide" href="#">
                            <img class="responsive-image" src="images/pictures/1t.jpg" alt="img">
                            <em>Fruit Pie</em>
                            <strong>$55.30</strong>
                            <u class="color-red-dark">Expires Soon</u>
                        </a>
                        <a class="swiper-slide" href="#">
                            <img class="responsive-image" src="images/pictures/3t.jpg" alt="img">
                            <em>Bread and Butter</em>
                            <strong>$55.30</strong>
                            <u class="color-green-dark">Free Shipping</u>
                        </a>
                        <a class="swiper-slide" href="#">
                            <img class="responsive-image" src="images/pictures/2t.jpg" alt="img">
                            <em>Frying Pans</em>
                            <strong>$55.30</strong>
                            <u class="color-blue-dark">5% Discount</u>
                        </a>
                        <a class="swiper-slide" href="#">
                            <img class="responsive-image" src="images/pictures/1t.jpg" alt="img">
                            <em>Fruit Pie</em>
                            <strong>$55.30</strong>
                            <u class="color-red-dark">Expires Soon</u>
                        </a>
                    </div>
                </div>
            </div>

            <div class="decoration decoration-margins"></div>

            <div class="footer footer-light">
                <a href="index.html" class="footer-logo"></a>
                <p class="footer-text">
                    The best mobile experience on Envato. Powered by pure speed, awesome features
                    and incredibly fast loading elements!
                </p>
                <div class="footer-socials">
                    <a href="#"><i class="ion-social-facebook"></i></a>
                    <a href="#"><i class="ion-social-instagram-outline"></i></a>
                    <a href="#"><i class="ion-social-youtube-outline"></i></a>
                    <a href="#"><i class="ion-social-twitter"></i></a>
                    <a href="#" class="show-share-bottom"><i class="ion-android-share-alt"></i></a>
                </div>
                <p class="copyright-text">&copy; Copyright <span class="copyright-year"></span>. All rights reserved</p>
            </div>

        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="ion-ios-arrow-up"></i></a>

    <div class="share-bottom share-light">
        <h3>Share Page</h3>
        <div class="share-socials-bottom">
            <a href="https://www.facebook.com/sharer/sharer.php?u=http://www.themeforest.net/">
                <i class="ion-social-facebook facebook-bg"></i>
                Facebook
            </a>
            <a href="https://twitter.com/home?status=Check%20out%20ThemeForest%20http://www.themeforest.net">
                <i class="ion-social-twitter twitter-bg"></i>
                Twitter
            </a>
            <a href="https://plus.google.com/share?url=http://www.themeforest.net">
                <i class="ion-social-googleplus google-bg"></i>
                Google
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=http://www.themeforest.net/&media=https://0.s3.envato.com/files/63790821/profile-image.jpg&description=Themes%20and%20Templates">
                <i class="ion-social-pinterest-outline pinterest-bg"></i>
                Pinterest
            </a>
            <a href="sms:">
                <i class="ion-ios-chatboxes-outline sms-bg"></i>
                Text
            </a>
            <a href="mailto:?&subject=Check this page out!&body=http://www.themeforest.net">
                <i class="ion-ios-email-outline mail-bg"></i>
                Email
            </a>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

