



<script>
function getStyles(properties) {
    if (!properties) return {};

    return {
        height: (properties.dimension && properties.dimension.height && properties.dimension.height.value || undefined) + 
                 (properties.dimension && properties.dimension.height && properties.dimension.height.unit || ''),
        width: (properties.dimension && properties.dimension.width && properties.dimension.width.value || undefined) + 
                (properties.dimension && properties.dimension.width && properties.dimension.width.unit || ''),

        color: properties.text && properties.text.color,
        fontFamily: properties.text && properties.text.fontFamily,

        paddingTop: (properties.padding && properties.padding.top && properties.padding.top.value || 0) + 
                    (properties.padding && properties.padding.unit || ''),
        paddingBottom: (properties.padding && properties.padding.bottom && properties.padding.bottom.value || 0) + 
                       (properties.padding && properties.padding.unit || ''),
        paddingRight: (properties.padding && properties.padding.right && properties.padding.right.value || 0) + 
                      (properties.padding && properties.padding.unit || ''),
        paddingLeft: (properties.padding && properties.padding.left && properties.padding.left.value || 0) + 
                     (properties.padding && properties.padding.unit || ''),

        marginTop: (properties.margin && properties.margin.top && properties.margin.top.value || 0) + 
                   (properties.margin && properties.margin.unit || ''),
        marginBottom: (properties.margin && properties.margin.bottom && properties.margin.bottom.value || 0) + 
                      (properties.margin && properties.margin.unit || ''),
        marginRight: (properties.margin && properties.margin.right && properties.margin.right.value || 0) + 
                     (properties.margin && properties.margin.unit || ''),
        marginLeft: (properties.margin && properties.margin.left && properties.margin.left.value || 0) + 
                    (properties.margin && properties.margin.unit || ''),

        background: (properties.background && properties.background.type === 'color') 
            ? properties.background.color 
            : 'url(' + (properties.background && properties.background.image && properties.background.image.source && properties.background.image.source.original || '') + ')',

        backgroundPosition: (properties.background && properties.background.type === 'image') ? 'center' : '',
        backgroundSize: (properties.background && properties.background.type === 'image') ? 'cover' : '',

        borderTop: (properties.border && properties.border.top && properties.border.top.value || 0) + 
                   (properties.border && properties.border.unit || '') + ' solid ' + 
                   (properties.border && properties.border.color || ''),
        borderBottom: (properties.border && properties.border.bottom && properties.border.bottom.value || 0) + 
                      (properties.border && properties.border.unit || '') + ' solid ' + 
                      (properties.border && properties.border.color || ''),
        borderRight: (properties.border && properties.border.right && properties.border.right.value || 0) + 
                     (properties.border && properties.border.unit || '') + ' solid ' + 
                     (properties.border && properties.border.color || ''),
        borderLeft: (properties.border && properties.border.left && properties.border.left.value || 0) + 
                    (properties.border && properties.border.unit || '') + ' solid ' + 
                    (properties.border && properties.border.color || ''),

        borderTopRightRadius: (properties.border && properties.border.rounded && properties.border.rounded.topright && properties.border.rounded.topright.value || 0) + 
                              (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
        borderBottomRightRadius: (properties.border && properties.border.rounded && properties.border.rounded.bottomright && properties.border.rounded.bottomright.value || 0) + 
                                 (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
        borderBottomLeftRadius: (properties.border && properties.border.rounded && properties.border.rounded.bottomleft && properties.border.rounded.bottomleft.value || 0) + 
                                (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
        borderTopLeftRadius: (properties.border && properties.border.rounded && properties.border.rounded.topleft && properties.border.rounded.topleft.value || 0) + 
                             (properties.border && properties.border.rounded && properties.border.rounded.unit || ''),
    };
}

console.log('www')
</script>

<div>
    <div id="footer_container" class="tw-py-24 md:tw-px-7" style="">
        <div class="">
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-3 md:tw-gap-8">
                Hello guys

            </div>
        </div>
    </div>
</div>