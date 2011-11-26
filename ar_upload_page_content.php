<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.Site.php';
require_once 'ar_edit_common.php';

//upload_page_content_from_file('app_files/tmp/page_content_1322053322_4ecceeca8d41c/jbb/index.php',469);
//exit;


if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('upload_header'):
   $data=prepare_values($_REQUEST,array(
    'parent'=>array('type'=>'string'),
                           'parent_key'=>array('type'=>'key')

                         ));

    upload_page_header($data);
    break;
case('upload_page_content'):
    $data=prepare_values($_REQUEST,array(
                             'page_key'=>array('type'=>'key'),

                         ));

    upload_page_content($data);
    break;


default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}






function upload_page_header($data){
   $page_key=$data['page_key'];

    if (isset($_FILES['file']['tmp_name'])) {

        $file_name=$_FILES['file']['tmp_name'];

        if ($_FILES['file']['type']=='application/zip') {

            upload_page_content_from_zip($page_key);
            return;
        }





    } else {
        $response= array('state'=>400,'msg'=>'no file');
        echo json_encode($response);
        return;
    }
}

function upload_page_content($data) {

    $page_key=$data['page_key'];

    if (isset($_FILES['file']['tmp_name'])) {

        $file_name=$_FILES['file']['tmp_name'];

        if ($_FILES['file']['type']=='application/zip') {

            upload_page_content_from_zip($page_key);
            return;
        }





    } else {
        $response= array('state'=>400,'msg'=>'no file');
        echo json_encode($response);
        return;
    }

}


function get_head_style($html) {

    $style='';

    $dom = new domDocument;
    ini_set( "display_errors", 0);
    $dom->loadHTML($html);
    ini_set( "display_errors", 1);
    $dom->preserveWhiteSpace = false;

    $heads = $dom->getElementsByTagName('head');

    $a=getArray($heads->item(0));

    if (isset($a['style'][0]['#cdata-section'])) {
        $style= $a['style'][0]['#cdata-section'];
    }

    return $style;


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



function upload_page_content_from_zip($page_key) {


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
        return;

    }
    elseif($number_html_files==1) {

        $response=upload_page_content_from_file("app_files/tmp/".preg_replace('/^\./',"page_content_".$folder_id,array_pop($html_files)),$page_key);
        chdir("../");
        recursiveDelete("page_content_".$folder_id);
           echo json_encode($response);
        
        exit;
    }
    else {


    }




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

function recursiveDelete($str) {

    //  print "$str";

    if (is_file($str)) {
        //      print "$str is file\n";
        return @unlink($str);
    }
    elseif(is_dir($str)) {
        //     print "$str is folser\n";
        $scan = glob(rtrim($str,'/').'/*');
        foreach($scan as $index=>$path) {
            recursiveDelete($path);
        }
        return @rmdir($str);
    }
}





function upload_page_content_from_file($file,$page_key) {
    global $editor;

    $page=new Page($page_key);
    $page->editor=$editor;

    $dirname=dirname($file);
    $html=file_get_contents($file);
    $php_free_html=remove_php_tags($html);
    $style=get_head_style($php_free_html);
    $page->update_field_switcher('Page Store CSS',$style);
    $content=clean_content($php_free_html);
    $content=upload_content_images($content,$dirname,$page_key);
    $page->update_field_switcher('Page Store Source',$content);
    $response= array('state'=>200);
    return $response;

}



function upload_content_images($html,$base_dir='',$page_key) {
    include_once('class.Image.php');

    $regexp = "<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {

            $_file= getcwd().'/'.$base_dir.'/'.$match[2];
            $_file= $base_dir.'/'.$match[2];
//print $_file;
            if (file_exists($_file)) {

                $image_data=array(
                                'file'=>$_file,
                                'source_path'=>'',
                                'name'=>basename($match[2])
                            );


                $image=new Image('find',$image_data,'create');
                $caption='';

                if ($image->id) {
                    $sql=sprintf("insert into `Image Bridge` values ('Page',%d,%d,'Yes',%s)",
                                 $page_key,
                                 $image->id,
                                 prepare_mysql($caption,false)
                                );
                    mysql_query($sql);

// $html=str_replace('alt="'.basename($match[2]).'"','alt=""',$html);
                    $html=str_replace('src="'.$match[2].'"','src="image.php?id='.$image->id.'"',$html);


                    // $_replacement=preg_replace('/src=(\"??)/','',$match[0]);

                    //       if(preg_match('/src=(\"|\').+(\")/i',$match[0],$xx)){
                    //           print_r($xx);
                    //       }

                    //   print_r($matchx);
                } else {

                }


            }

//    print_r($match);
            //$url=preg_replace("/^https?\:\/\//",'',$match[2]);
            //$link_label=$match[3];

            //$links[$url]=$link_label;



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
    $regexp = "<\?php\s*show_products\(.+\).*\?>";
    if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {

//print_r($match);



        }
    }

    $html=preg_replace("/<\?php\s*show_products\(.+\).*\?>/",'{$page->display_product_form_list()}',$html);



    $html=preg_replace("/<\?php.*\?>/msU",'',$html);
    return $html;

}
