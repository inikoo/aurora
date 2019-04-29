<?php


require_once 'common.php';


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
        $data['Image File Size']   = filesize($tmp_file);

        $data['Image Data']        = '';
        $data['Image Thumbnail Data']='';
        $data['Image Small Data']='';
        $data['Image Large Data']='';

        $data['Image Path']='img/db/'.$data['Image File Checksum'][0].'/'.$data['Image File Checksum'][1].'/'.$data['Image File Checksum'].'.'.$file_extension;

        print_r($data);

        if(rename($tmp_file, $data['Image Path'] )){
            $image->fast_update($data);

        }else{
            exit('error cant migrate image');
        }













    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

