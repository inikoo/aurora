

{assign var="footerTheme1" value=array(
            "code" => "FooterTheme1",
            "name" => "Footer 1",
            "bluprint" => array(
                array(
                    "key" => "body",
                    "icon" => "far fa-line-columns",
                    "name" => "Body",
                    "type" => "body",
                    "bluprint" => array(
                        array(
                            "key" => array("container", "properties"),
                            "name" => "Body",
                            "type" => "body",
                        )
                    )
                ),
            ),
        )}


<div>
    <div id="footer_container" class="tw-py-24 md:tw-px-7" style="">
        <div class="">
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-3 md:tw-gap-8">
                Hello guys +++  {$footerTheme1}

            </div>
        </div>
    </div>
</div>