<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  10 February 2020  22:35::06  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2015 Inikoo

 Version 3.0
*/

include_once 'ar_web_common_logged_in.php';


ini_set('zlib.output_compression', 'Off');

if (!isset($_REQUEST['file']) || empty($_REQUEST['file'])) {
    header("HTTP/1.0 400 Bad Request");
    exit;
}


$download_id = $_REQUEST['file'];


$sql = "SELECT `Download Filename`,`Download Data` FROM `Download Dimension` WHERE `Download Key`=? and `Download Creator Type`='Customer' and `Download Creator Key`=?";

$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $download_id,
        $customer->id
    )
);
if ($row = $stmt->fetch()) {
    $file_path = $row['Download Filename'];
    $blob_data = $row['Download Data'];
} else {
    header("HTTP/1.0 404 Not Found");
    exit;
}


$path_parts = pathinfo($file_path);
$file_name  = $path_parts['basename'];
$file_ext   = $path_parts['extension'];
$file_path  = 'server_files/tmp/'.$file_name;


file_put_contents($file_path, $blob_data);

$is_attachment = isset($_REQUEST['stream']) ? false : true;

if (is_file($file_path)) {
    $file_size = filesize($file_path);
    $file      = @fopen($file_path, "rb");
    if ($file) {
        // set the headers, prevent caching


        header("Pragma: public");
        header("Expires: -1");
        header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
        header("Content-Disposition: attachment; filename=\"$file_name\"");

        if ($is_attachment) {
            header("Content-Disposition: attachment; filename=\"$file_name\"");
        } else {
            header('Content-Disposition: inline;');
        }

        // set the mime type based on extension, add yours if needed.
        $ctype_default = "application/octet-stream";

        $content_types = array(
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "mp3" => "audio/mpeg",
            "mpg" => "video/mpeg",
            "avi" => "video/x-msvideo",
        );
        $ctype         = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
        header("Content-Type: ".$ctype);


        $range = '';

        if (isset($_SERVER['HTTP_RANGE'])) {
            $begin = 0;
            $end   = $file_size - 1;

            if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
                $begin = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                }

                $range = $begin.'-'.$end;
            } else {
                if (file_exists($file_path)) {
                    unlink($file_path);
                }

                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                exit;
            }


        }

        if ($range == '') {
            $seek_start = '';
            $seek_end   = '';
        } else {

            list($seek_start, $seek_end) = explode('-', $range, 2);

        }

        //set start and end based on range (if set), else set defaults
        //also check for invalid ranges.
        $seek_end   = (empty($seek_end))
            ? ($file_size - 1)
            : min(
                abs(intval($seek_end)), ($file_size - 1)
            );
        $seek_start = (empty($seek_start)
            || $seek_end < abs(
                intval($seek_start)
            )) ? 0 : max(abs(intval($seek_start)), 0);


        if ($seek_start > 0 || $seek_end < ($file_size - 1)) {
            header('HTTP/1.1 206 Partial Content');
            header(
                'Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$file_size
            );
            header('Content-Length: '.($seek_end - $seek_start + 1));
        } else {
            header("Content-Length: $file_size");
        }

        header('Accept-Ranges: bytes');

        $sql = "INSERT INTO  `Download Attempt Dimension` (`Download Attempt Download Key`,`Download Attempt Creator Type`,`Download Attempt Creator Key`,`Download Attempt Date`)  VALUES (?,?,?,?)";
        $db->prepare($sql)->execute(
            array(
                $download_id,
                'Customer',
                $customer->id,
                gmdate('Y-m-d H:i:s')
            )
        );

        $sql = "UPDATE `Download Dimension` SET `Download Attempts`=`Download Attempts`+1 ,`Download Attempt Last Date`=? ,`Download State`='Downloaded'  WHERE `Download Key`=?";


        $db->prepare($sql)->execute(
            array(
                gmdate('Y-m-d H:i:s'),
                $download_id
            )
        );


        set_time_limit(0);
        fseek($file, $seek_start);

        while (!feof($file)) {
            print(@fread($file, 1024 * 8));
            ob_flush();
            flush();
            if (connection_status() != 0) {
                @fclose($file);
                exit;
            }
        }

        // file save was a success
        fclose($file);

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        exit;
    } else {
        // file couldn't be opened
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        header("HTTP/1.0 500 Internal Server Error");

        exit;
    }
} else {
    // file does not exist
    header("HTTP/1.0 404 Not Found");
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    exit;
}


