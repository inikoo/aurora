<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.Site.php';
require_once 'class.Image.php';

require_once 'ar_edit_common.php';

//upload_page_content_from_file('app_files/tmp/page_content_1322053322_4ecceeca8d41c/jbb/index.php',469);
//exit;

//upload_header_from_file('app_files/tmp/page_content_1322354660_4ed187e4c1d0c/header_aw/header.html',array('parent'=>'site','parent_key'=>1,'original_filename'=>'xx'));
//exit;
if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}


$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('upload_header'):
case('upload_footer'):
    $data=prepare_values($_REQUEST,array(
                             'parent'=>array('type'=>'string'),
                             'parent_key'=>array('type'=>'key'),
                             'use_file'=>array('type'=>'string')

                         ));
    $data['tipo']=$tipo;
    process_uploaded_files($data);
    break;



case('upload_page_content'):
    $data=prepare_values($_REQUEST,array(
                             'parent_key'=>array('type'=>'key'),
                             'parent'=>array('type'=>'string'),
                             'use_file'=>array('type'=>'string')
                         ));
    $data['tipo']=$tipo;

    process_uploaded_files($data);
    break;


default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}








function process_uploaded_files($data) {
//print_r($_FILES);
    if (isset($_FILES['file']['tmp_name']) and $_FILES['file']['tmp_name']) {

        $file_name=$_FILES['file']['tmp_name'];

        if (preg_match('/^application.*zip.*/',$_FILES['file']['type'])) {
            $data['original_filename']=$_FILES['file']['name'];
            upload_from_zip($data);
            return;
        }





    } else {
        $poidsMax = ini_get('upload_max_filesize');
        $msg=_("Your feet is too big, maximum allowed size here is")." $poidsMax";
        $response= array('state'=>400,'msg'=>$msg);
        echo json_encode($response);
        return;
    }

}

function get_footer($html) {
    $html=preg_replace("/^.*\<\/head\>/msU",'',$html);
    $html=preg_replace("/<\/html\>.*$/msU",'',$html);

    $html=preg_replace("/^.*\<body.*\>/msU",'',$html);
    $html=preg_replace("/<\/body>\s*$/",'',$html);


//print $html;
    //$a=getArray($div);
    return $html;
}

function get_header($html) {
    $html=preg_replace("/^.*\<\/head\>/msU",'',$html);
    $html=preg_replace("/<\/html\>.*$/msU",'',$html);

    $html=preg_replace("/^.*\<body.*\>/msU",'',$html);
    $html=preg_replace("/<\/body>\s*$/",'',$html);


//print $html;
    //$a=getArray($div);
    return $html;
}

function get_head_styles($dom,$html) {
    $style=array();

    $heads = $dom->getElementsByTagName('head');
    $a=getArray($heads->item(0));

    if (isset($a['style'])) {
        if (is_array($a['style']))    {
            foreach($a['style'] as $style_data) {
                if (isset($style_data['#cdata-section'])) {
                    $_style=preg_replace('/^\<\!\-\-/','',$style_data['#cdata-section']);
                    $_style=preg_replace('/\-\-\>$/','',$_style);
                    $style[]= $_style;
                }
            }
        } else if (is_string($a['style'])) {

            $_style=preg_replace('/^\<\!\-\-/','',$a['style']);
            $_style=preg_replace('/\-\-\>$/','',$_style);
            $style[]= $_style;
        }

    }
    return $style;
}

function get_head_scripts($dom,$html) {
    $script=array();
    $heads = $dom->getElementsByTagName('head');
    $a=getArray($heads->item(0));

    //print_r($a);
    if (isset($a['script'])) {
        foreach($a['script'] as $script_data) {
            if (isset($script_data['#cdata-section'])) {
                $_script=$script_data['#cdata-section'];
                $script[]= $_script;
            }
        }
    }
    return $script;
}



function getArray($node) {
    $array = false;

    if ($node->hasAttributes()) {
        foreach ($node->attributes as $attr) {
            $array[$attr->nodeName] = $attr->nodeValue;
        }
    }

    if ($node->hasChildNodes()) {
        if ($node->childNodes->length == 1) {
            $array[$node->firstChild->nodeName] = $node->firstChild->nodeValue;
        } else {
            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeType != XML_TEXT_NODE) {
                    $array[$childNode->nodeName][] = getArray($childNode);
                }
            }
        }
    }

    return $array;
}



function upload_from_zip($data) {


    $folder_id=date('U').'_'.uniqid();
    $base_dir="app_files/tmp/page_content_".$folder_id;

    mkdir($base_dir, 0777);

    $zip_name= $base_dir.'/upload.zip';
    move_uploaded_file($_FILES['file']['tmp_name'],$zip_name);
    chdir($base_dir);
    unzip('upload.zip');
    $zip_folders=dua_get_files('.');
    $html_files=array();
    foreach($zip_folders as $zip_folder) {
        foreach (glob("$zip_folder/*.php") as $filename) {
            $html_files[]=$filename;
        }
        foreach (glob("$zip_folder/*.html") as $filename) {
            $html_files[]=$filename;
        }

    }
    chdir("../../../");


    $number_html_files=count($html_files);

    if ($number_html_files==0) {
        $response= array('state'=>400,'msg'=>_('no HTML/PHP file found'));
        echo json_encode($response);


    }
    elseif($number_html_files==1) {

        switch ($data['tipo']) {
        case 'upload_page_content':
            $response=upload_page_content_from_file("app_files/tmp/".preg_replace('/^\./',"page_content_".$folder_id,array_pop($html_files)),$data);
            break;
        case 'upload_header':
            $response=upload_header_from_file("app_files/tmp/".preg_replace('/^\./',"page_content_".$folder_id,array_pop($html_files)),$data);
            break;
        case 'upload_footer':
            $response=upload_footer_from_file("app_files/tmp/".preg_replace('/^\./',"page_content_".$folder_id,array_pop($html_files)),$data);
            break;

        default:

            break;
        }


        //deleteAll("app_files/tmp/page_content_".$folder_id);
        echo json_encode($response);


    }
    else {

        //  print $data['use_file'].' xxxx';

        if ($data['use_file'] and in_array($data['use_file'],$html_files)) {

            switch ($data['tipo']) {
            case 'upload_page_content':
                $response=upload_page_content_from_file("app_files/tmp/".preg_replace('/^\./',"page_content_".$folder_id,$data['use_file']),$data);
                break;
            case 'upload_header':
                $response=upload_header_from_file("app_files/tmp/".preg_replace('/^\./',"page_content_".$folder_id,$data['use_file']),$data);
                break;
            case 'upload_footer':
                $response=upload_footer_from_file("app_files/tmp/".preg_replace('/^\./',"page_content_".$folder_id,$data['use_file']),$data);
                break;

            default:

                break;
            }


            //deleteAll("app_files/tmp/page_content_".$folder_id);
            echo json_encode($response);



        } else {


            $response= array('state'=>201,'list'=>$html_files);
            echo json_encode($response);

        }
    }


    deleteAll("app_files/tmp/page_content_".$folder_id);
    return;
}


function unzip($file) {

    $zip=zip_open($file);
    if (!$zip) {
        return("Unable to proccess file '{$file}'");
    }

    $e='';

    while ($zip_entry=zip_read($zip)) {
        $zdir=dirname(zip_entry_name($zip_entry));
        $zname=zip_entry_name($zip_entry);

        if (!zip_entry_open($zip,$zip_entry,"r")) {
            $e.="Unable to proccess file '{$zname}'";
            continue;
        }
        if (!is_dir($zdir)) mkdirr($zdir,0777);


        $zip_fs=zip_entry_filesize($zip_entry);
        if (empty($zip_fs)) continue;

        $zz=zip_entry_read($zip_entry,$zip_fs);

        $z=fopen($zname,"w");
        fwrite($z,$zz);
        fclose($z);
        zip_entry_close($zip_entry);

    }
    zip_close($zip);

    return($e);
}


function dua_get_files($path) {
    foreach (glob($path . "/*", GLOB_ONLYDIR) as $filename) {
        $dir_paths[] = $filename;
    }
    return $dir_paths;
}


function mkdirr($pn,$mode=null) {

    if (is_dir($pn)||empty($pn)) return true;
    $pn=str_replace(array('/', ''),DIRECTORY_SEPARATOR,$pn);

    if (is_file($pn)) {
        trigger_error('mkdirr() File exists', E_USER_WARNING);
        return false;
    }

    $next_pathname=substr($pn,0,strrpos($pn,DIRECTORY_SEPARATOR));
    if (mkdirr($next_pathname,$mode)) {
        if (!file_exists($pn)) {
            return mkdir($pn,$mode);
        }
    }
    return false;
}





function deleteAll($directory, $empty = false) {
    if (substr($directory,-1) == "/") {
        $directory = substr($directory,0,-1);
    }

    if (!file_exists($directory) || !is_dir($directory)) {
        return false;
    }
    elseif(!is_readable($directory)) {
        return false;
    }
    else {
        $directoryHandle = opendir($directory);

        while ($contents = readdir($directoryHandle)) {
            if ($contents != '.' && $contents != '..') {
                $path = $directory . "/" . $contents;

                if (is_dir($path)) {
                    deleteAll($path);
                } else {
                    unlink($path);
                }
            }
        }

        closedir($directoryHandle);

        if ($empty == false) {
            if (!rmdir($directory)) {
                return false;
            }
        }

        return true;
    }
}





function upload_header_from_file($file,$data) {
    include_once('class.PageHeader.php');
    switch ($data['parent']) {
    case 'site':
        $parent=new Site($data['parent_key']);

        $site_key=$parent->id;
        break;
    case 'page':
        $parent=new Page($data['parent_key']);
        $site_key=$parent->data['Page Site Key'];
        break;
    default:
        exit;
        break;
    }

    $html=file_get_contents($file);

    $html=remove_php_tags($html);

    $dom = new domDocument;
    ini_set( "display_errors", 0);
    $dom->loadHTML($html);
    ini_set( "display_errors", 1);
    $dom->preserveWhiteSpace = false;

    $styles=get_head_styles($dom,$html);
    $scripts=get_head_scripts($dom,$html);

    $content=get_header($html);



    if ($content=='') {
        $response= array('state'=>400,'msg'=>_('Header not found'));
        return $response;
    }


    $page_header=new PageHeader();



    $page_header_data=array(
                          'Page Header Name'=>$data['original_filename'],
                          'Site Key'=>$site_key,
                          'Default Site'=>'No',
                      );
    $page_header->create($page_header_data);


    $content=upload_content_images($content,dirname($file),array('subject'=>'Page Header','subject_key'=>$page_header->id));
    $page_header->update(array('Template'=>$content));



    foreach($styles as $_key=>$style) {
        $style=upload_content_images($style,dirname($file),array('subject'=>'Page Header','subject_key'=>$page_header->id));

        $name='style_header'.$parent->id.'_'.$_key;
        $sql=sprintf("insert into `Page Store External File Dimension` (`Page Store External File Name`,`Page Store External File Type`,`Page Store External File Content`) values (%s,'CSS',%s) ",
                     prepare_mysql($name),
                     prepare_mysql($style)

                    );
        mysql_query($sql);
        $external_file_id=mysql_insert_id();
        $sql=sprintf("insert into `Page Header External File Bridge` values (%d,%d,%s) ",
                     $external_file_id,
                     $page_header->id,
                     prepare_mysql('CSS')
                    );
        mysql_query($sql);
    }


    foreach($scripts as $_key=>$script) {


        $name='script_header'.$parent->id.'_'.$_key;
        $sql=sprintf("insert into `Page Store External File Dimension` (`Page Store External File Name`,`Page Store External File Type`,`Page Store External File Content`) values (%s,'Javascript',%s) ",
                     prepare_mysql($name),
                     prepare_mysql($script)

                    );
        mysql_query($sql);
        $external_file_id=mysql_insert_id();
        $sql=sprintf("insert into `Page Header External File Bridge` values (%d,%d,%s) ",
                     $external_file_id,
                     $page_header->id,
                     prepare_mysql('Javascript')
                    );
        mysql_query($sql);
        // exit;
    }
    $page_header->update_image();
    $response= array('state'=>200);
    return $response;
}

function upload_footer_from_file($file,$data) {
    include_once('class.PageFooter.php');
    switch ($data['parent']) {
    case 'site':
        $parent=new Site($data['parent_key']);

        $site_key=$parent->id;
        break;
    case 'page':
        $parent=new Page($data['parent_key']);
        $site_key=$parent->data['Page Site Key'];
        break;
    default:
        exit;
        break;
    }

    $html=file_get_contents($file);

    $html=remove_php_tags($html);

    $dom = new domDocument;
    ini_set( "display_errors", 0);
    $dom->loadHTML($html);
    ini_set( "display_errors", 1);
    $dom->preserveWhiteSpace = false;

    $styles=get_head_styles($dom,$html);
    $scripts=get_head_scripts($dom,$html);

    $content=get_footer($html);



    if ($content=='') {
        $response= array('state'=>400,'msg'=>_('Footer not found'));
        return $response;
    }


    $page_footer=new PageFooter();



    $page_footer_data=array(
                          'Page Footer Name'=>$data['original_filename'],
                          'Site Key'=>$site_key,
                          'Default Site'=>'No',
                      );
    $page_footer->create($page_footer_data);


    $content=upload_content_images($content,dirname($file),array('subject'=>'Page Footer','subject_key'=>$page_footer->id));
    $page_footer->update(array('Template'=>$content));



    foreach($styles as $_key=>$style) {
        $style=upload_content_images($style,dirname($file),array('subject'=>'Page Footer','subject_key'=>$page_footer->id));

        $name='style_footer'.$parent->id.'_'.$_key;
        $sql=sprintf("insert into `Page Store External File Dimension` (`Page Store External File Name`,`Page Store External File Type`,`Page Store External File Content`) values (%s,'CSS',%s) ",
                     prepare_mysql($name),
                     prepare_mysql($style)

                    );
        mysql_query($sql);
        $external_file_id=mysql_insert_id();
        $sql=sprintf("insert into `Page Footer External File Bridge` values (%d,%d,%s) ",
                     $external_file_id,
                     $page_footer->id,
                     prepare_mysql('CSS')
                    );
        mysql_query($sql);
    }


    foreach($scripts as $_key=>$script) {


        $name='script_footer'.$parent->id.'_'.$_key;
        $sql=sprintf("insert into `Page Store External File Dimension` (`Page Store External File Name`,`Page Store External File Type`,`Page Store External File Content`) values (%s,'Javascript',%s) ",
                     prepare_mysql($name),
                     prepare_mysql($script)

                    );
        mysql_query($sql);
        $external_file_id=mysql_insert_id();
        $sql=sprintf("insert into `Page Footer External File Bridge` values (%d,%d,%s) ",
                     $external_file_id,
                     $page_footer->id,
                     prepare_mysql('Javascript')
                    );
        mysql_query($sql);
        // exit;
    }
    $page_footer->update_image();
    $response= array('state'=>200);
    return $response;
}


function upload_page_content_from_file($file,$data) {
    global $editor;

    $page_key=$data['parent_key'];

    $page=new Page($page_key);
    $page->editor=$editor;


    $html=file_get_contents($file);

    $html=extract_products_info($html);
    $html=remove_php_tags($html);
    $dom = new domDocument;
    ini_set( "display_errors", 0);
    $dom->loadHTML($html);
    ini_set( "display_errors", 1);
    $dom->preserveWhiteSpace = false;

    $styles=get_head_styles($dom,$html);

    $sql=sprintf("select * from `Page Store External File Bridge` where `Page Key`=%d  ",$page->id);

    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {


        $sql=sprintf("delete from `Page Store External File Dimension` where `Page Store External File Key`=%d",$row['Page Store External File Key']);

        mysql_query($sql);

    }
    $sql=sprintf("delete from  `Page Store External File Bridge` where `Page Key`=%d  ",$page->id);

    mysql_query($sql);
    foreach($styles as $_key=>$style) {
        $style=upload_content_images($style,dirname($file),array('subject'=>'Page','subject_key'=>$page->id));

        $name='style_page'.$page->id.'_'.$_key;
        $sql=sprintf("insert into `Page Store External File Dimension` (`Page Store External File Name`,`Page Store External File Type`,`Page Store External File Content`) values (%s,'CSS',%s) ",
                     prepare_mysql($name),
                     prepare_mysql($style)

                    );
        mysql_query($sql);
        $external_file_id=mysql_insert_id();
        $sql=sprintf("insert into `Page Store External File Bridge` values (%d,%d,%s) ",
                     $external_file_id,
                     $page->id,
                     prepare_mysql('CSS')
                    );
        mysql_query($sql);
    }



    $html=clean_content($html);

    $html=upload_content_images($html,dirname($file),array('subject'=>'Page','subject_key'=>$page->id));


    $page->update_field_switcher('Page Store Source',$html,'no history');

    $history_data=array(
                      'History Abstract'=>_('Page content uploaded'),
                      'History Details'=>'',

                      'Indirect Object'=>'Page Store Source',
                      'Indirect Object Key'=>''
                  );
    $page->add_history($history_data);

    $page->update_button_products();
    $page->update_list_products();
    $page->update_number_products();

    $response= array('state'=>200);
    return $response;

}



function upload_content_images($html,$base_dir='',$parent_data) {
    include_once('class.Image.php');

    $regexp = "<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {
            $_file= getcwd().'/'.$base_dir.'/'.$match[2];
            $_file= $base_dir.'/'.$match[2];

            if (file_exists($_file)) {
                $image_data=array('file'=>$_file,'source_path'=>'','name'=>basename($match[2])
                                 );
                $image=new Image('find',$image_data,'create');
                $caption='';
                if ($image->id) {
                    $sql=sprintf("insert into `Image Bridge` values (%s,%d,%d,'Yes',%s)",
                                 prepare_mysql($parent_data['subject']),
                                 $parent_data['subject_key'],
                                 $image->id,
                                 prepare_mysql($caption,false)
                                );
                    mysql_query($sql);
                    $html=str_replace('src="'.$match[2].'"','src="public_image.php?id='.$image->id.'"',$html);
                } else {

                }
            }
        }
    }

//   print $html;

    $regexp = "url\((.+)\);";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {


            $file=preg_replace('/^\s*(\'|\")\s*/','',$match[1]);
            $file=preg_replace('/\s*(\'|\")\s*$/','',$file);




            $_file= $base_dir.'/'.$file;
            //     print "$_file\n";
            if (file_exists($_file)) {
                $image_data=array('file'=>$_file,'source_path'=>'','name'=>basename($file));
                $image=new Image('find',$image_data,'create');
                $caption='';
                if ($image->id) {
                    $sql=sprintf("insert into `Image Bridge` values (%s,%d,%d,'Yes',%s)",
                                 prepare_mysql($parent_data['subject']),
                                 $parent_data['subject_key'],
                                 $image->id,
                                 prepare_mysql($caption,false)
                                );
                    mysql_query($sql);
                    $html=str_replace($match[1],"'public_image.php?id=".$image->id."'",$html);

                } else {
                    //print "cont insert image\n";

                }
            } else {

                //print "image not exists\n";
            }
        }
    }


    return $html;

}

function clean_content($html) {

    $regexp = "alt=\".*\"";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {


            if (preg_match('/\<wbr\>/',$match[0])) {

                $_replacement=preg_replace('/\<wbr\>/','',$match[0]);
                $html=str_replace($match[0],$_replacement,$html);

            }
            //$url=preg_replace("/^https?\:\/\//",'',$match[2]);
            //$link_label=$match[3];

            //$links[$url]=$link_label;



        }
    }

    $html=preg_replace("/^.*\<\/head\>/msU",'',$html);
    $html=preg_replace("/<\/html\>.*$/msU",'',$html);

    $html=preg_replace("/^.*\<body.*\>/msU",'',$html);

    //PPP only >>>
    $html=preg_replace("/^<center>/msU",'',$html);
    $html=_trim($html);
    $html=preg_replace("/^<div.*relative.*>\s*/i",'',$html);
    //PPP only <<<


    $html=preg_replace("/<\/body>\s*$/",'',$html);

    //PPP only >>>
    $html=preg_replace("/<\/center>\s*$/",'',$html);
    $html=preg_replace("/<\/div>\s*$/",'',$html);

    $html=preg_replace("/\<div style=\"position\:absolute; left:0px; top:0px;.*>\s*<div style=\"text-align:left;\">\s*<\/div>\s*<\/div>/msU",'',$html);

    $min_top=99999;
    $tops=false;
    $regexp = " top:(\d+)px; ";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {
            $tops=true;
            if ($match[1]<$min_top)
                $min_top=$match[1];

        }
    }


    if ($tops and $min_top>120) {
        $min_top+10;
        $html=preg_replace('/ top:(\d+)px; /e','" top:".($1-$min_top)."px; " ',$html);

    }

    //PPP only <<<
//print $html;
//exit;
    return $html;
}




function remove_php_tags($html) {




    $html=preg_replace("/<\?php.*\?>/msU",'',$html);
    $html=preg_replace("/<\?.*\?>/msU",'',$html);
    return $html;

}

function extract_products_info($html) {

//$html=str_replace('->lo','lo',$html);
//$html=str_replace('->ge','ge',$html);
    $regexp = "<\?(.*)\?>";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {

            if (preg_match('/new Family/',$match[1])) {
                $html=str_replace($match[0],'{$page->display_list()}',$html);
            }

        }
    }

    $regexp = "<\?(.*)\?>";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {

            if (preg_match('/new Product/',$match[1])) {

                if (preg_match('/\$product_code=(.+);/',$match[1],$_match)) {

                    $html=str_replace($match[0],'{$page->display_button('.$_match[1].')}',$html);

                }

                //    $html=str_replace($match[0],'{$page->display_list()}',$html);
            }

        }
    }


    $html=preg_replace("/<\?php\s*show_product\((.+)\).*\?>/",'{$page->display_button($1)}',$html);

    $html=preg_replace("/<\?php\s*show_products\(.+\).*\?>/siU",'{$page->display_list()}',$html);


    return $html;
}