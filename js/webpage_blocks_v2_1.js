/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 April 2020  14:26::44  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.1*/

$(function () {

    $(document).on( "click", ".wrap .txt", function() {

        edit_panel_text($(this).closest('.wrap'))

    });

    $(document).on( "click", ".product_header_text ", function() {


        $(this).closest('.wrap').addClass('sortable-disabled').find('.panel_txt_control').removeClass('hide');

        $(this).uniqueId();

        // panel.find('.panel_txt_control').removeClass('hide')


        set_up_froala_editor($(this).attr('id'))

    });

    $(document).on("input propertychange", ".item_overlay_item_header_text", function () {


        $(this).closest('.wrap').find('.category_block .item_header_text').html($(this).html())

    });

    $(document).on( "click", ".close_category_block", function() {

        $(this).closest('.wrap').addClass('sortable-disabled');

        $(this).closest('.wrap').find('.edit_icon').removeClass('hide');


        //var title=$(this).closest('.item_overlay').find('.item_header_text').html()
        //$(this).closest('.wrap').find('.category_block .item_header_text').html(title)


        $(this).closest('.wrap').removeClass('sortable-disabled');
        $(this).closest('.wrap').find('.edit').removeClass('hide');

        $(this).closest('.item_overlay').addClass('hide')

    });

    $(document).on('click', '.category_wrap img.panel', function (e) {
        open_image_control_panel(this,'category_categories');
    });

    $(document).on('click', '.product_wrap img.panel', function (e) {
        open_image_control_panel(this,'category_products');
    });

    $(document).on( "click", ".section_items .move_to_other_section", function() {

        $('#sections_list_tbody tr').removeClass('hide');

        if($(this).closest('.section').hasClass('non_anchor')){
            var index=$(this).closest('.section').index()-1;

            $('#sections_list_tbody tr:eq('+index+') ').addClass('hide')

        }




        $('#sections_list').removeClass('hide').offset({
            left: $(this).offset().left,top: $(this).offset().top
        }).data('element',this)






    });

    $(document).on("click", "#sections_list td.button", function () {


        var block_key = $('#sections_list').data('block_key');


        var element = $('#sections_list').data('element');

        var index = $(this).closest('tr').index();

        if (!$(this).closest('tr').hasClass('anchor')) {
            index = index + 1
        }

        console.log(index);
        $('#category_sections_' + block_key + ' .section:eq(' + index + ') .section_items').append(element.closest('.category_wrap'));
        $('#sections_list').addClass('hide');


        $(element).closest('.item_overlay').addClass('hide')

    });

    $(document).on("input propertychange", ".section .title", function () {
        var index = $(this).closest('.section').index()-1;

        parent.category_categories_section_title_changed(index,$(this).html());
        $('#sections_list_tbody tr:eq('+index+') ._title').html($(this).html());

        console.log(index)

    });

    $(document).on( "dblclick", ".blackboard_text", function() {

        if($(this).hasClass('froala_on')){
            return;
        }




        $(this).draggable( 'destroy' ).resizable('destroy').addClass('editing froala_on');



        set_up_froala_editor($(this).attr('id'));

        parent.open_blackboard_text_edit_view(
            $(this).closest('._block').data('block_key'),
            $(this).attr('id')

        )
    });

    $( ".section_items" ).sortable({
        connectWith: ".connectedSortable",
        cancel: ".sortable-disabled",
        stop: function (event, ui) {
            $('#save_button',window.parent.document).addClass('save button changed valid')
        }
    });

    $(document).on('input paste', '[contenteditable=true]', function (e) {
        $('#save_button', window.parent.document).addClass('save button changed valid')

    });


    $(document).on('click', '._image_tooltip', function (e) {

        if ($('#image_tooltip_edit').hasClass('hide')) {

            $('#image_tooltip_edit').removeClass('hide').offset({
                top: $(this).offset().top - 30, left: $(this).offset().left + $(this).width() + 10
            }).data('element', $(this)).find('input').val($(this).attr('tooltip')).focus()
        } else {
            set_image_tooltip()
        }

    });



    $("form").on('submit', function (e) {
        e.preventDefault();
        e.returnValue = false;
    });


    $(document).on('click', '.blk_images .image img', function (e) {
        open_image_control_panel(this,'images');
    });

    $(document).on('click', '.blackboard  img', function (e) {
        open_image_control_panel(this,'blackboard');
    });

    $(document).on('click', '  .video', function (e) {
        open_video_control_panel(this);
    });


    $(document).on('click', '#image_control_panel .caption_align i', function (e) {


        $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected');
        $(this).removeClass('super_discreet').addClass('selected');

        element = $('#image_control_panel').data('element');

        $(element).attr('display_class', $(this).attr('display_class'));

        $(element).closest('figure').find('figcaption').removeClass('caption_left caption_right caption_center caption_hide').addClass($(this).attr('display_class'));
        console.log($(element));

        $('#save_button', window.parent.document).addClass('save button changed valid')

    });

    $(document).on('click', '.simple_line_item_icon', function (e) {


        $('#simple_line_icons_control_center').removeClass('hide').offset({
            top: $(this).offset().top - 69, left: $(this).offset().left + $(this).width()
        }).data('item', $(this))


    });


    $(document).ready(function () {
        resize_banners();
    });

    $(window).resize(function () {
        resize_banners();

    });
});

function resize_banners() {
    $('.iframe').each(function (i, obj) {
        $(this).css({
            height: $(this).width() * $(this).attr('h') / $(this).attr('w')})
    });
}

function set_up_froala_editor(key) {






     default_font=$('body').data('default_font');

     var buttons={
         'moreText': {
             'buttons': ['h1', 'h2', 'h3','h4','h5','h6','bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting',


             ],'buttonsVisible': 8

         },
         'moreParagraph': {
             'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
         },
         'moreRich': {
             'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
         },
         'moreMisc': {
             'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help'],
             'align': 'right',
             'buttonsVisible': 2
         }
     };


     var editor_container=$('#'+key);


    var isActive = function (cmd) {
        var blocks = this.selection.blocks();

        if (blocks.length) {
            var blk = blocks[0];
            var tag = 'N';
            var default_tag = this.html.defaultTag();
            if (blk.tagName.toLowerCase() != default_tag && blk != this.el) {
                tag = blk.tagName;
            }
        }

        if (['LI', 'TD', 'TH'].indexOf(tag) >= 0) {
            tag = 'N';
        }

        return tag.toLowerCase() == cmd;
    }

    FroalaEditor.DefineIcon('h1', {NAME: '<strong>H1</strong>', template: 'text'});
    FroalaEditor.DefineIcon('h2', {NAME: '<strong>H2</strong>', template: 'text'});
    FroalaEditor.DefineIcon('h3', {NAME: '<strong>H3</strong>', template: 'text'});
    FroalaEditor.DefineIcon('h4', {NAME: '<strong>H4</strong>', template: 'text'});
    FroalaEditor.DefineIcon('h5', {NAME: '<strong>H5</strong>', template: 'text'});
    FroalaEditor.DefineIcon('h6', {NAME: '<strong>H6</strong>', template: 'text'});



    FroalaEditor.RegisterCommand('h1', {
        title: 'Heading 1',
        callback: function (cmd, val, params) {
            if (isActive.apply(this, [cmd])) {
                this.paragraphFormat.apply('N');
            }
            else {
                this.paragraphFormat.apply(cmd);
            }
        },
        refresh: function ($btn) {
            $btn.toggleClass('fr-active', isActive.apply(this, [$btn.data('cmd')]));
        }
    });

    FroalaEditor.RegisterCommand('h2', {
        title: 'Heading 2',
        callback: function (cmd, val, params) {
            if (isActive.apply(this, [cmd])) {
                this.paragraphFormat.apply('N');
            }
            else {
                this.paragraphFormat.apply(cmd);
            }
        },
        refresh: function ($btn) {
            $btn.toggleClass('fr-active', isActive.apply(this, [$btn.data('cmd')]));
        }
    });

    FroalaEditor.RegisterCommand('h3', {
        title: 'Heading 3',
        callback: function (cmd, val, params) {
            if (isActive.apply(this, [cmd])) {
                this.paragraphFormat.apply('N');
            }
            else {
                this.paragraphFormat.apply(cmd);
            }
        },
        refresh: function ($btn) {
            $btn.toggleClass('fr-active', isActive.apply(this, [$btn.data('cmd')]));
        }
    });

    FroalaEditor.RegisterCommand('h4', {
        title: 'Heading 4',
        callback: function (cmd, val, params) {
            if (isActive.apply(this, [cmd])) {
                this.paragraphFormat.apply('N');
            }
            else {
                this.paragraphFormat.apply(cmd);
            }
        },
        refresh: function ($btn) {
            $btn.toggleClass('fr-active', isActive.apply(this, [$btn.data('cmd')]));
        }
    });

    FroalaEditor.RegisterCommand('h5', {
        title: 'Heading 5',
        callback: function (cmd, val, params) {
            if (isActive.apply(this, [cmd])) {
                this.paragraphFormat.apply('N');
            }
            else {
                this.paragraphFormat.apply(cmd);
            }
        },
        refresh: function ($btn) {
            $btn.toggleClass('fr-active', isActive.apply(this, [$btn.data('cmd')]));
        }
    });

    FroalaEditor.RegisterCommand('h6', {
        title: 'Heading 6',
        callback: function (cmd, val, params) {
            if (isActive.apply(this, [cmd])) {
                this.paragraphFormat.apply('N');
            }
            else {
                this.paragraphFormat.apply(cmd);
            }
        },
        refresh: function ($btn) {
            $btn.toggleClass('fr-active', isActive.apply(this, [$btn.data('cmd')]));
        }
    });



    var editor=new FroalaEditor('#'+key, {
        key: $('body').data('fel'),
        toolbarInline: true,
        charCounterCount: false,
        toolbarButtons: buttons,
        toolbarButtonsMD: buttons,
        toolbarButtonsSM: buttons,
        toolbarButtonsXS: buttons,
        defaultImageDisplay: 'inline',
        fontSize: ['8', '10', '12', '14','16', '18', '30', '60', '96'],
        fontFamily: {
            default_font: 'Default',
            'Arial,Helvetica,sans-serif': 'Arial',
            'Impact,Charcoal,sans-serif': 'Impact',
            'Tahoma,Geneva,sans-serif': 'Tahoma'
        },
        zIndex: 1000,
        pastePlain: true,
        imageUploadURL: '/ar_upload.php',
        imageUploadParams: {
            tipo: 'upload_images', parent: 'webpage', parent_key: $('#blocks').data('webpage_key'),   parent_object_scope: 'Froala',    parent_object_scope: JSON.stringify({ scope: 'block', block_key: key}), response_type: 'froala'

        },
        imageUploadMethod: 'POST',
        imageMaxSize: 5 * 1024 * 1024,
        imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],


        events: {
            'contentChanged': function () {
                $('#save_button', window.parent.document).addClass('save button changed valid')
            }
        }
    });





    editor_container.data('editor',editor)




}

function move_block(pre, post) {

    if (post > pre) {
        $('#blocks ._block:eq(' + pre + ')').insertAfter('#blocks ._block:eq(' + post + ')');
    } else {
        $('#blocks ._block:eq(' + pre + ')').insertBefore('#blocks ._block:eq(' + post + ')');
    }
}

function set_up_blackboard(key){

    $('#blackboard_'+key).resizable(
        {
            minHeight:20,
            minWidth:1240,
            maxWidth:1240,
            stop: function (event, ui) {
                $('#save_button',window.parent.document).addClass('save button changed valid')
            }
        }
    );



}

function add_image_to_blackboard(key){


    var datetime = new Date();
    var id='blackboard_image_'+datetime.getTime();

    $('<div id='+id+' class="blackboard_image" style="width:200px;" ></div>').appendTo($('#blackboard_'+key));

    $('<img  title="" link="" alt="" src="/art/nopic_trimmed.jpg" style="width: 200px;"   data-image_website="" data-src="/art/nopic_trimmed.jpg" data-width="200" >').on('load',function (evt) {
        set_up_blackboard_image(id)
    }).appendTo($('#'+id)  );

}


function add_text_to_blackboard(key){
    var datetime = new Date();
    var id='blackboard_text_'+datetime.getTime();
    text = $('<div  id='+id+' class="blackboard_text" style="position:absolute;width:150px;height:150px;" ><h1>Bla bla</h1><p>bla bla bla.</p></div>').appendTo($('#blackboard_'+key));

    set_up_blackboard_text(id)

}

function set_up_images(key){
    $('#block_'+key+' .blk_images').sortable({
        cancel: 'figcaption,input,textarea,button,select,option,[contenteditable]',
        stop: function (event, ui) {

            $('#save_button',window.parent.document).addClass('save button changed valid')


        }
    })
}

function delete_image(){
    console.log($('#image_control_panel').data('element'));

    if($('#image_control_panel').data('element').hasClass('panel')){
        $('#image_control_panel').data('element').closest('.wrap').remove()

    }else{
        $('#image_control_panel').data('element').remove()

    }
    $('#save_button',window.parent.document).addClass('save button changed valid');
    close_image_control_panel()
}


function delete_video(){


    $('#video_control_panel').data('element').closest('.wrap').remove();


    close_video_control_panel()
}

function set_up_blackboard_image(img_id){



    $('#'+img_id).find('img').resizable({
        containment: $('#'+img_id).closest('.blackboard'),
        aspectRatio:true,
        stop: function (event, ui) {
            $('#save_button',window.parent.document).addClass('save button changed valid')
        }

    });


    $('#'+img_id).draggable(
        {
            containment: $('#'+img_id).closest('.blackboard'),
            scroll: false,
            start: function(event, ui) {
                isDraggingMedia = true;
            },
            stop: function (event, ui) {
                isDraggingMedia = false;
                $('#save_button',window.parent.document).addClass('save button changed valid')


            }
        }

    )




}

function set_up_blackboard_text(text_id){

    $('#'+text_id).resizable({
        containment: $('#'+text_id).closest('.blackboard'),
        stop: function (event, ui) {
            $('#save_button',window.parent.document).addClass('save button changed valid')


        }

    });


    $('#'+text_id).draggable(
        {
            containment: $('#'+text_id).closest('.blackboard'),
            scroll: false,
            start: function(event, ui) {
            },
            stop: function (event, ui) {
                $('#save_button',window.parent.document).addClass('save button changed valid')


            }
        }

    )

}

function exit_blackboard_text_edit(id){


    $('#'+id).removeClass('editing froala_on').data('editor').destroy();


    set_up_blackboard_text(id)



}

function delete_blackboard_text_edit(id){
    $('#'+id).data('editor').destroy().remove()
}

function refresh_see_also(block_key,items,auto_items,auto_last_updated){

    $('#block_'+block_key+' .see_also').html(items).data('auto_items',auto_items).data('auto',true).data('auto_last_updated',auto_last_updated);

    $('#save_button',window.parent.document).addClass('save button changed valid')


}

function update_category_products_item_headers(block_key,value){

    var product_wrap=$('#block_'+block_key).find('.products');

    // console.log(value)

    if(value=='on'){
        product_wrap.removeClass('no_items_header');

        $('#block_'+block_key+' img.panel').each(function (i, img) {
            $(img).data('height',330);

            if($(img).attr('src')=='https://placehold.co/470x290'){
                $(img).attr('src','https://placehold.co/470x330')
            }

            if($(img).attr('src')=='https://placehold.co/226x290'){
                $(img).attr('src','https://placehold.co/226x330')
            }
            if($(img).data('src')=='https://placehold.co/226x290'){
                $(img).data('src','https://placehold.co/226x330')
            }

            if($(img).attr('src')=='https://placehold.co/470x290'){
                $(img).attr('src','https://placehold.co/470x330')
            }
            if($(img).data('src')=='https://placehold.co/470x290'){
                $(img).data('src','https://placehold.co/470x330')
            }

            if($(img).attr('src')=='https://placehold.co/714x290'){
                $(img).attr('src','https://placehold.co/714x330')
            }
            if($(img).data('src')=='https://placehold.co/714x290'){
                $(img).data('src','https://placehold.co/714x330')
            }

            if($(img).attr('src')=='https://placehold.co/958x290'){
                $(img).attr('src','https://placehold.co/958x330')
            }
            if($(img).data('src')=='https://placehold.co/1202x290'){
                $(img).data('src','https://placehold.co/1202x330')
            }

        })

    }else{
        product_wrap.addClass('no_items_header');

        $('#block_'+block_key+' img.panel').each(function (i, img) {



            $(img).data('height',290);


            if($(img).attr('src')=='https://placehold.co/226x330'){
                $(img).attr('src','https://placehold.co/226x290')
            }
            if($(img).data('src')=='https://placehold.co/226x330'){
                $(img).data('src','https://placehold.co/226x290')
            }

            if($(img).attr('src')=='https://placehold.co/470x330'){
                $(img).attr('src','https://placehold.co/470x290')
            }
            if($(img).data('src')=='https://placehold.co/470x330'){
                $(img).data('src','https://placehold.co/470x290')
            }

            if($(img).attr('src')=='https://placehold.co/714x330'){
                $(img).attr('src','https://placehold.co/714x290')
            }
            if($(img).data('src')=='https://placehold.co/714x330'){
                $(img).data('src','https://placehold.co/714x290')
            }

            if($(img).attr('src')=='https://placehold.co/958x330'){
                $(img).attr('src','https://placehold.co/958x290')
            }
            if($(img).data('src')=='https://placehold.co/1202x330'){
                $(img).data('src','https://placehold.co/1202x290')
            }


        })

    }

}

function sort_category_products_items(block_key,type){


    $('#block_'+block_key+' .products').data('sort',type);

    var panel_index={

    };


    if(type!='Manual') {


        $('#block_' + block_key + ' .products .product_wrap:not(.type_product)').each(function (i, panel) {

            $(panel).uniqueId();
            panel_index[$(panel).attr('id')] = $(panel).index()


        });





        if (type == 'Code') {

            $('#block_' + block_key + ' .products .product_wrap').sort(sort_li).appendTo('#block_' + block_key + ' .products ');

            function sort_li(a, b) {
                return ($(b).data('sort_code')) < ($(a).data('sort_code')) ? 1 : -1;
            }
        } else if (type == 'Code_desc') {

            $('#block_' + block_key + ' .products .product_wrap').sort(sort_li).appendTo('#block_' + block_key + ' .products ');

            function sort_li(a, b) {
                return ($(b).data('sort_code')) > ($(a).data('sort_code')) ? 1 : -1;
            }
        } else if (type == 'Name') {

            $('#block_' + block_key + ' .products .product_wrap').sort(sort_li).appendTo('#block_' + block_key + ' .products ');

            function sort_li(a, b) {
                return ($(b).data('sort_name')) < ($(a).data('sort_name')) ? 1 : -1;
            }
        }


        $.each(panel_index, function (panel_id, index) {

            $('#' + panel_id).insertAfter('#block_' + block_key + ' .products .product_wrap:eq(' + index + ')');

        });

        product_sort_index=[];
        $('#block_'+block_key+' .products .type_product').each(function (i, product) {
            product_sort_index.push($(product).attr('id'))
        })

    }

    $('#save_button',window.parent.document).addClass('save button changed valid')

}

function remove_product_from_products(element){

    $(element).closest('.wrap').remove();
    $('#save_button',window.parent.document).addClass('save button changed valid')


}

function toggle_block_title(block_key,value){
    if(value){
        $('#block_'+block_key+' .products_title').removeClass('hide')
    }else{
        $('#block_'+block_key+' .products_title').addClass('hide')

    }
    $('#save_button',window.parent.document).addClass('save button changed valid')


}


function toggle_see_also_auto(block_key,value){

    $('#block_'+block_key+' .see_also').data('auto',value);


    if(value){
        $( "#block_"+block_key+" .see_also " ).sortable("destroy").addClass('no_edit');



        $( "#block_"+block_key+" .item_overlay " ).addClass('hide')

    }else{




        $( "#block_"+block_key+" .see_also " ).removeClass('no_edit').sortable({
            cancel: ".sortable-disabled,.edit_icon",
            update:function (event, ui) {
                $('#save_button',window.parent.document).addClass('save button changed valid')



            },

        })

    }
    $('#save_button',window.parent.document).addClass('save button changed valid')
}

function add_guest_to_category_categories(block_key,section_index,category_element){
    $('#category_sections_'+block_key+' .section:eq('+section_index+') .section_items').append($(category_element))
}

function add_product_to_products_block(block_key,product_element){
    $('#block_'+block_key+' .products').append(product_element)
}


function add_item_to_see_also(block_key,item){
    $('#block_'+block_key+' .see_also').append(item)

}

function add_panel(block_key,type,size,scope,scope_metadata){



    if(scope=='category_categories'){
        var item_class='category_wrap wrap';
        height=220;
    }else if(scope=='category_products'){
        var item_class='product_wrap wrap type_'+type;
        height=scope_metadata;
    }



    if(type=='text'){
        switch(size){
            case 1:
                panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px" size_class="panel_1" data-padding="20" class="txt panel_1">bla bla</div></div>');

                break;
            case 2:
                panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px"  size_class="panel_2" data-padding="20" class="txt panel_2">bla bla</div></div>');

                break;
            case 3:
                panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px" size_class="panel_3"  data-padding="20" class="txt panel_3">bla bla</div></div>');

                break;
            case 4:
                panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px" size_class="panel_4"  data-padding="20" class="txt panel_4">bla bla</div></div>');

                break;
            case 5:
                panel=$('<div class="'+item_class+'" data-type="text"><div style="padding:20px"  size_class="panel_5" data-padding="20" class="txt panel_5">bla bla</div></div>');

                break;

        }




    }
    else if(type=='image'){

        switch(size){
            case 1:
                panel=$('<div class="'+item_class+'" data-type="image"><img class="panel panel_1" size_class="panel_1" alt="" link="" data-image_website="" data-height="'+height+'" data-src="https://placehold.co/226x'+height+'" src="https://placehold.co/226x'+height+'"  /></div>');

                break;
            case 2:
                panel=$('<div class="'+item_class+'" data-type="image"><img class="panel  panel_2"  size_class="panel_2" alt="" link="" data-image_website=""  data-height="'+height+'" data-src="https://placehold.co/470x'+height+'"  src="https://placehold.co/470x'+height+'"  /></div>');

                break;
            case 3:
                panel=$('<div class="'+item_class+'" data-type="image""><img class="panel panel_3"   size_class="panel_3" alt="" link="" data-image_website=""  data-height="'+height+'" data-src="https://placehold.co/714x'+height+'"  src="https://placehold.co/714x'+height+'"  /></div>');

                break;
            case 4:
                panel=$('<div class="'+item_class+'" data-type="image"><img class="panel  panel_4"   size_class="panel_4" alt="" link="" data-image_website=""  data-height="'+height+'" data-src="https://placehold.co/958x'+height+'"  src="https://placehold.co/958x'+height+'"  /></div>');

                break;
            case 5:
                panel=$('<div class="'+item_class+'" data-type="image"><img class="panel  panel_5"   size_class="panel_5" alt="" link=""  data-image_website="" data-height="'+height+'"  data-src="https://placehold.co/1202x'+height+'"  src="https://placehold.co/1202x'+height+'"  /></div>');

                break;


        }


    } else if(type=='video'){
        panel=$('<div class="'+item_class+'" data-type="video"><div  size_class="panel_2" class="video  empty panel_2" video_id="" ></div></div>')

    }

    if(scope=='category_categories'){
        $('#category_sections_'+block_key+' .section:eq('+scope_metadata+') .section_items').prepend(panel)

    }else if(scope=='category_products'){
        $('#block_'+block_key+' .products').prepend(panel)

    }


    if(type=='text'){
        $( "#panel_txt_control .panel_txt_control" ).clone().prependTo(panel);
        edit_panel_text(panel)
    }else if(type=='video'){
        open_video_control_panel(panel.find('.video'));
    }





    $('#save_button',window.parent.document).addClass('save button changed valid')

}

function edit_panel_text(panel) {

    panel.find('.txt').uniqueId();

    panel.find('.panel_txt_control').removeClass('hide');

    var panel_id = panel.addClass('sortable-disabled').find('.txt').attr('id');

    //console.log(panel_id)

    set_up_froala_editor(panel_id)

}

function close_panel_text(element) {

    $(element).closest('.panel_txt_control').addClass('hide').closest('.wrap').removeClass('sortable-disabled').find('.txt').data('editor').destroy();

    $(element).closest('.wrap').find('.txt').addClass('fr-view')

}


function close_product_header_text(element){
    $(element).closest('.panel_txt_control').addClass('hide').closest('.wrap').removeClass('sortable-disabled').find('.product_header_text').data('editor').destroy();

    $(element).closest('.wrap').find('.product_header_text').addClass('fr-view')
}

function delete_panel_text(element){
    $(element).closest('.wrap').remove();
}


function update_product_item_headers(block_key,value){

    var product_wrap=$('#block_'+block_key).find('.products');

    if(value=='on'){
        product_wrap.removeClass('no_items_header');
        $('#block_'+block_key+' .delete_product').css({ 'top':'90px'})


    }else{
        product_wrap.addClass('no_items_header');
        $('#block_'+block_key+' .delete_product').css({ 'top':'50px'})


    }

}

function open_image_control_panel(element,type) {



    if (!$('#image_control_panel').hasClass('hide')) {
        return
    }



    var block_key=$(element).closest('_block').data('block_key');

    var image_options={ };
    if(type=='images'){
        image_options['set_width']=$(element).data('width');

        $('#image_control_panel .caption_tr').removeClass('hide');
        $('#update_images_block_image').attr('name','images');
        $('#image_control_panel').find('.image_caption').val($(element).closest('figure').find('figcaption').html())


    }else if(type=='category_categories'){

        $('#update_images_block_image').attr('name','category_categories');
        $('#image_control_panel .caption_tr').addClass('hide');
        height=220;

        switch($(element).attr('size_class')){
            case 'panel_1':
                $('#image_control_panel .image_size').html('(226x'+height+')');
                image_options['fit_to_canvas']='226x'+height+'';

                break;
            case 'panel_2':
                $('#image_control_panel .image_size').html('(470x'+height+')');
                image_options['fit_to_canvas']='470x'+height+'';

                break;
            case 'panel_3':
                $('#image_control_panel .image_size').html('(714x'+height+')');
                image_options['fit_to_canvas']='714x'+height+'';

                break;
            case 'panel_4':
                $('#image_control_panel .image_size').html('(958x'+height+')');
                image_options['fit_to_canvas']='958x'+height+'';

                break;
            case 'panel_5':
                $('#image_control_panel .image_size').html('(1202x'+height+')');
                image_options['fit_to_canvas']='1202x'+height+'';

                break;
        }



    }else if(type=='category_products'){

        $('#update_images_block_image').attr('name','category_products');
        $('#image_control_panel .caption_tr').addClass('hide');

        var height=$(element).data('height');
        switch($(element).attr('size_class')){
            case 'panel_1':
                $('#image_control_panel .image_size').html('(226x'+height+')');
                image_options['fit_to_canvas']='226x'+height+'';

                break;
            case 'panel_2':
                $('#image_control_panel .image_size').html('(470x'+height+')');
                image_options['fit_to_canvas']='470x'+height+'';

                break;
            case 'panel_3':
                $('#image_control_panel .image_size').html('(714x'+height+')');
                image_options['fit_to_canvas']='714x'+height+'';

                break;
            case 'panel_4':
                $('#image_control_panel .image_size').html('(958x'+height+')');
                image_options['fit_to_canvas']='958x'+height+'';

                break;
            case 'panel_5':
                $('#image_control_panel .image_size').html('(1202x'+height+')');
                image_options['fit_to_canvas']='1202x'+height+'';

                break;
        }



    }else{

        $('#image_control_panel .caption_tr').addClass('hide');


        $('#update_images_block_image').attr('name','blackboard_image')


    }


// top: .25 * ($(element).offset().top + $(element).height()) / 2

    $('#image_control_panel').removeClass('hide').offset({
        top:  $(element).offset().top, left: $(element).offset().left
    }).addClass('in_use').data('element', $(element));



    console.log($( '#blocks' ).width());
    console.log( $('#image_control_panel').offset().left+$('#image_control_panel').width());

    if($('#image_control_panel').offset().left+$('#image_control_panel').width()>$( '#blocks' ).width()){
        $('#image_control_panel').offset({
            left: $('#image_control_panel').offset().left-($('#image_control_panel').offset().left+$('#image_control_panel').width()-$( '#blocks' ).width())
        })
    }



    $('#image_control_panel').find('.image_control_panel_upload_td input').attr('block_key',block_key).data('options',image_options);


    $('#image_control_panel').find('.image_tooltip').val($(element).attr('alt'));
    $('#image_control_panel').find('.image_link').val($(element).attr('link'));





    $('#image_control_panel').attr('old_image_src', $(element).attr('src'));

    $('#image_control_panel').find('.caption_align i').addClass('super_discreet').removeClass('selected');
    $('#image_control_panel').find('.caption_align i.' + $(element).attr('display_class')).removeClass('super_discreet').addClass('selected');

    $('#image_control_panel').find('.image_upload_from_iframe').data('img', $(element))



}

function open_video_control_panel(element) {



    if (!$('#video_control_panel').hasClass('hide')) {
        return
    }








    $('#video_control_panel').removeClass('hide').offset({
        top:  $(element).offset().top+40, left: $(element).offset().left
    }).addClass('in_use').data('element', $(element));


    if($('#video_control_panel').offset().left+$('#video_control_panel').width()>$( '#blocks' ).width()){
        $('#video_control_panel').offset({
            left: $('#video_control_panel').offset().left-($('#video_control_panel').offset().left+$('#video_control_panel').width()-$( '#blocks' ).width())
        })
    }




    $('#video_control_panel').find('.video_link').val($(element).attr('video_id'))


}


function close_image_control_panel() {


    var image = $('#image_control_panel').data('element');

    image.attr('src', $('#image_control_panel').attr('old_image_src'));


    $('#image_control_panel').addClass('hide')

}


function close_video_control_panel(){
    $('#video_control_panel').addClass('hide')
}


function update_image() {

    // var   image=  $('.blk_images .image:nth-child('+$('#image_control_panel').attr('image_index')+') img')

    var image = $('#image_control_panel').data('element');

    image.attr('alt', $('#image_control_panel').find('.image_tooltip').val());
    image.attr('link', $('#image_control_panel').find('.image_link').val());

    var caption_class = $('#image_control_panel').find('.caption_align i.selected').attr('display_class');
    image.attr('display_class', caption_class);

    image.closest('figure').find('figcaption').removeClass('caption_left caption_right caption_center caption_hide').addClass(caption_class);
    image.closest('figure').find('figcaption').html($('#image_control_panel').find('.image_caption').val());

    $('#image_control_panel').addClass('hide');
    $('#save_button', window.parent.document).addClass('save button changed valid')


}

function update_video(){

    var video = $('#video_control_panel').data('element');

    var video_link=$('#video_control_panel').find('.video_link').val();


    video.removeClass('empty');

    video.attr('video_id', video_link);


    video.html('<iframe width="470" height="330" frameallowfullscreen="" src="https://www.youtube.com/embed/'+video_link+'?rel=0&amp;controls=0&amp;showinfo=0"></iframe><div class="block_video" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>');



    close_video_control_panel();
}



function  change_image_template(block_key, template) {

    var image_blocks= $('#block_'+block_key).find('.blk_images');
    image_blocks.html( $('#template_' + template).html() );
    image_blocks.attr('template',template)


}

function change_text_template(block_key, template) {


    var text_blocks= $('#block_'+block_key).find('.text_blocks');

    var old_template=text_blocks.data('template');

    if(old_template==template)return;

    if(template=='12' || template=='21' || template=='13' || template=='31'){
        var _template='2';
    }else if(template=='211'){
        var _template='3';
    }else{
        var _template=template;
    }



    text_blocks.data('template',template).html($('#text_template_'+_template).html());

    text_blocks.removeClass('text_template_'+old_template);
    text_blocks.addClass('text_template_'+template);
    if(template=='1'){
        text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor');
        set_up_froala_editor('block_'+block_key+'_0_editor')
    }else if(template=='2' || template=='12' || template=='21'  || template=='13' || template=='31'){
        text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor');
        set_up_froala_editor('block_'+block_key+'_0_editor');
        text_blocks.find('div:nth-child(2)').attr('id','block_'+block_key+'_1_editor');
        set_up_froala_editor('block_'+block_key+'_1_editor')
    } else if(template=='3' || template=='211'){
        text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor');
        set_up_froala_editor('block_'+block_key+'_0_editor');
        text_blocks.find('div:nth-child(2)').attr('id','block_'+block_key+'_1_editor');
        set_up_froala_editor('block_'+block_key+'_1_editor');
        text_blocks.find('div:nth-child(3)').attr('id','block_'+block_key+'_2_editor');
        set_up_froala_editor('block_'+block_key+'_2_editor')
    }else if(template=='4'){
        text_blocks.find('div:nth-child(1)').attr('id','block_'+block_key+'_0_editor');
        set_up_froala_editor('block_'+block_key+'_0_editor');
        text_blocks.find('div:nth-child(2)').attr('id','block_'+block_key+'_1_editor');
        set_up_froala_editor('block_'+block_key+'_1_editor');
        text_blocks.find('div:nth-child(3)').attr('id','block_'+block_key+'_2_editor');
        set_up_froala_editor('block_'+block_key+'_2_editor');
        text_blocks.find('div:nth-child(4)').attr('id','block_'+block_key+'_3_editor');
        set_up_froala_editor('block_'+block_key+'_3_editor')
    }



}

function toggle_view_category_categories(block_key,view){
    if(view=='backstage') {

        $('#category_sections_'+block_key+' .item_overlay').removeClass('hide')

    }else{
        $('#category_sections_'+block_key+' .item_overlay').addClass('hide')
        //  $('.panel_controls').addClass('hide')
    }

    // $('#add_item_dialog').addClass('hide')

}



function show_edit_category_categories_section() {

}

function move_category_categories_sections(block_key,pre,post){
    if (post > pre) {

        $('#category_sections_'+block_key+' .non_anchor:eq(' + pre + ')').insertAfter('#category_sections_'+block_key+' .non_anchor:eq(' + post + ')');


        $('#sections_list_tbody tr:eq(' + pre + ')').insertAfter('#sections_list_tbody tr:eq(' + post + ')');




    } else {


        $('#category_sections_'+block_key+' .non_anchor:eq(' + pre + ')').insertBefore('#category_sections_'+block_key+' .non_anchor:eq(' + post + ')');
        $('#sections_list_tbody tr:eq(' + pre + ')').insertBefore('#sections_list_tbody tr:eq(' + post + ')');


    }
}

function delete_category_categories_section(block_key,index){

    var section=$('#category_sections_'+block_key+' .non_anchor:eq(' + index + ')');




    $('#category_sections_'+block_key+' .non_anchor:eq(' + index + ') .category_wrap').each(function (i, category_wrap) {
        $('#category_sections_'+block_key+' .anchor .section_items').append($(category_wrap))
    });

    section.remove();

}

function set_image_tooltip() {

    value = $('#image_tooltip_edit').find('input').val();
    $('#image_tooltip_edit').addClass('hide').data('element').attr('tooltip', value);

    if (value == '') {
        $('#image_tooltip_edit').data('element').removeClass('fa-comment-alt').addClass('fa-comment')
    } else {
        $('#image_tooltip_edit').data('element').addClass('fa-comment-alt').removeClass('fa-comment')

    }
    $('#save_button', window.parent.document).addClass('save button changed valid')


}

function create_static_banner() {


    var slider = new MasterSlider();


    slider.setup("masterslider", {
        width: 1300, height: 768, minHeight: 0,

        fullwidth: true, space: 5
        //autoHeight:true,
        //view:"mask"

        //space           : 0,
        //start           : 1,
        //grabCursor      : false,
        //swipe           : false,
        //mouse           : false,
        //keyboard        : false,
        //layout          : "fullwidth",
        //wheel           : false,
        //autoplay        : false,
        //instantStartLayers:false,
        //loop            : false,
        //shuffle         : false,
        //preload         : 0,
        //heightLimit     : true,
        //autoHeight      : false,
        //smoothHeight    : true,
        //endPause        : false,
        //overPause       : false,
        //fillMode        : "fill",
        //centerControls  : true,
        //startOnAppear   : false,
        //layersMode      : "center",
        //autofillTarget  : "",
        //hideLayers      : false,
        //fullscreenMargin: 0,
        //speed           : 20,
        //dir             : "h",
        //parallaxMode    : 'swipe',
        //view            : "basic"
    });
    slider.control('arrows');
    slider.control('bullets', {
        autohide: false, dir: "v", align: "top"
    });
    MSScrollParallax.setup(slider, 66, 69, true);

}