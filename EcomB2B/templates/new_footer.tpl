

{assign var="footerTheme1" value=array(
            "code" => "FooterTheme1",
            "name" => "Footer 1",
            "bluprint" => array("zzz")
)}

console.log('ddd', {$footerTheme1})

</script>

<div>
    <div id="footer_container" class="tw-py-24 md:tw-px-7" style="">
        <div class="">
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-3 md:tw-gap-8">
                Hello guys ---- {$footerTheme1}

            </div>
        </div>
    </div>
</div>