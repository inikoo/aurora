<?php


require_once 'common.php';

include_once 'utils/image_functions.php';

$sql  = sprintf('select  `Website Key`  from `Website Dimension`    ');
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {

    $website = get_object('Website', $row['Website Key']);

    $website->fast_update(
        array(
            'Website Mobile Style' => preg_replace('/image_root/', 'wi', $website->data['Website Mobile Style'])
        )
    );


    $settings = $website->settings;
    // $style = $website->style;


    $tmp = array();
    foreach ($website->style as $style_data) {
        $tmp[trim($style_data[0]).'|'.trim($style_data[1])] = $style_data[2];

    }
    $style = array();
    foreach ($tmp as $_key => $_value) {
        $_tmp    = preg_split('/\|/', $_key);
        $style[] = array(
            $_tmp[0],
            $_tmp[1],
            $_value
        );
    }

    $height = 60;
    $width  = 80;
    foreach ($style as $style_data) {
        if ($style_data[0] == '#header_logo' and $style_data[1] == 'flex-basis') {
            $width = floatval($style_data[2]);
        }
        if ($style_data[0] == '#top_header' and $style_data[1] == 'height') {
            $height = floatval($style_data[2]);
        }
    }


    if (isset($settings['logo_website'])) {
        $settings['logo_website'] = preg_replace('/image_root/', 'wi', $settings['logo_website']);
        if (preg_match('/id=(\d+)/', $settings['logo_website'], $matches)) {
            $settings['logo_website_website'] = 'wi.php?id='.$matches[1].'&s='.get_image_size($matches[1], $width * 2, $height * 2, 'fit_highest');
        }

    } else {
        $settings['logo_website']         = '';
        $settings['logo_website_website'] = '';
    }

    if (isset($settings['favicon'])) {
        $settings['favicon'] = preg_replace('/image_root/', 'wi', $settings['favicon']);
        if (preg_match('/id=(\d+)/', $settings['favicon'], $matches)) {
            $settings['favicon_website'] = 'wi.php?id='.$matches[1].'&s=32x32';
        }
    } else {
        $settings['favicon']         = '';
        $settings['favicon_website'] = '';
    }

    //    print_r($settings);

    $website->fast_update(
        array(
            'Website Settings' => json_encode($settings)
        )
    );
}


$sql = sprintf('SELECT * FROM `Website Header Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $sql = sprintf(
            'update  `Website Header Dimension` set  `Website Header Data`=%s  where `Website Header Key`=%d', prepare_mysql(preg_replace('/image_root/', 'wi', $row['Website Header Data'])), $row['Website Header Key']
        );
        $db->exec($sql);


    }
}

$sql = sprintf('SELECT * FROM `Website Footer Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $sql = sprintf(
            'update  `Website Footer Dimension` set  `Website Footer Data`=%s  where `Website Footer Key`=%d', prepare_mysql(preg_replace('/image_root/', 'wi', $row['Website Footer Data'])), $row['Website Footer Key']
        );
        $db->exec($sql);


    }
}


$sql = sprintf('select  `Image Key`  from `Image Dimension`   where `Image Data` is not null  ');


if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {


        $data = array();

        $image = get_object('image', $row2['Image Key']);


        $tmp_file = $image->save_image_to_file('/tmp', '_'.$image->get('Image File Checksum'));

        $tmp_file = '/tmp/'.$tmp_file;

        //print "$tmp_file\n";

        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $whitelist_type = array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/x-icon'
        );

        if (!in_array($file_mime = $finfo->file($tmp_file), $whitelist_type)) {
            print ("Uploaded file is not an valid image format").' '.$file_mime;
            exit;
        }

        $data['Image MIME Type'] = $file_mime;

        $size_data = getimagesize($tmp_file);

        if (!$size_data) {
            print _("Error opening the image").', '._('please contact support');
            exit;
        }

        switch ($data['Image MIME Type']) {
            case 'image/x-icon':
                $file_extension = 'ico';
                break;
            default:
                $file_extension = preg_replace('/image\//', '', $data['Image MIME Type']);
        }

        $data['Image File Checksum'] = md5_file($tmp_file);
        $data['Image Width']         = $size_data[0];
        $data['Image Height']        = $size_data[1];
        $data['Image File Format']   = $file_extension;
        $data['Image File Size']     = filesize($tmp_file);

        $data['Image Data']           = '';
        $data['Image Thumbnail Data'] = '';
        $data['Image Small Data']     = '';
        $data['Image Large Data']     = '';

        $data['Image Path'] = 'img/db/'.$data['Image File Checksum'][0].'/'.$data['Image File Checksum'][1].'/'.$data['Image File Checksum'].'.'.$file_extension;

        // print_r($data);

        if (rename($tmp_file, $data['Image Path'])) {
            $image->fast_update($data);

        } else {
            exit('error cant migrate image');
        }


        $sql = sprintf(
            "select `Image Subject Key` from `Image Subject Bridge`  where  `Image Subject Is Public`='Yes'  
            and `Image Subject Object` in ('Webpage','Store Product','Site Favicon','Product','Family','Department','Page','Page Header','Page Footer','Page Header Preview','Page Footer Preview','Page Preview','Site Menu','Site Search','Category')
             and `Image Subject Image Key`=%d 
             limit 1", $image->id
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {

                // print_r($row);

                if (!is_dir('img/public_db/'.$data['Image File Checksum'][0])) {
                    mkdir('img/public_db/'.$data['Image File Checksum'][0]);
                }


                if (!is_dir('img/public_db/'.$data['Image File Checksum'][0].'/'.$data['Image File Checksum'][1])) {
                    mkdir('img/public_db/'.$data['Image File Checksum'][0].'/'.$data['Image File Checksum'][1]);
                }
                chdir('img/public_db/'.$data['Image File Checksum'][0].'/'.$data['Image File Checksum'][1]);


                if (!file_exists(preg_replace('/.*\//', '', $data['Image Path']))) {
                    if (!symlink(
                        preg_replace('/img\/db/', '../../../db', $data['Image Path']), preg_replace('/.*\//', '', $data['Image Path'])


                    )) {
                        exit('can  not create symlink');
                    }
                }


                chdir('../../../../');

            }
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;


}

