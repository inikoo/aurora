<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20-06-2019 16:33:26 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf('select  `Image Key`  from `Image Dimension`   ');


if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {


        $image      = get_object('image', $row2['Image Key']);
        $checksum   = $image->get('Image File Checksum');
        $image_path = $image->get('Image Path');

        if (!preg_match('/^[a-f0-9]{32}$/i', $checksum)) {
            exit('wring checksum');
        }


        $sql = sprintf(
            "select `Image Subject Key` from `Image Subject Bridge`  where  `Image Subject Is Public`='Yes'  
            and `Image Subject Object` in ('Webpage','Product','Category','Website')
             and `Image Subject Image Key`=%d 
             limit 1", $image->id
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {

                if (!is_dir('img/public_db/'.$checksum[0])) {
                    mkdir('img/public_db/'.$checksum[0]);
                }


                if (!is_dir('img/public_db/'.$checksum[0].'/'.$checksum[1])) {
                    mkdir('img/public_db/'.$checksum[0].'/'.$checksum[1]);
                }



                chdir('img/public_db/'.$checksum[0].'/'.$checksum[1]);

              //  print 'img/public_db/'.$checksum[0].'/'.$checksum[1]."\n";

                $_tmp = preg_replace('/.*\//', '', $image_path);
                print "$_tmp\n";
                if (!file_exists($_tmp)) {

                    if (!symlink(
                        preg_replace('/img\/db/', '../../../db', $image_path), $_tmp


                    )) {
                        exit('can  not create symlink');
                    }
                }


                chdir('../../../../');

            } else {


                $public_db_path = preg_replace('/img\/db/', 'img/public_sb', $image_path);
                if (file_exists($public_db_path)) {
                    unlink($public_db_path);
                }


                $mask = 'img/public_cache/'.$checksum[0].'/'.$checksum[1]."/".$checksum."_*";
                array_map("unlink", glob($mask));


            }
        }


    }
}
