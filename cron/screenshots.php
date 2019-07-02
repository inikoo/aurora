<?php
use \Gumlet\ImageResize;
use Spatie\ImageOptimizer\OptimizerChainFactory;
require_once 'common.php';
$optimizerChain = OptimizerChainFactory::create();


$sql=sprintf('SELECT `Page Key`,`Webpage URL` FROM `Page Store Dimension` where  `Webpage State`="Online" and `Page Key`= 2562  ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {


            $tmp_file_root=sprintf('server_files/tmp/original_%d_%d',gmdate('U'),$row['Page Key']);

            $cmd=sprintf('node cron/screenshots.js --file_root="%s" --url="%s" &',$tmp_file_root,addslashes($row['Webpage URL']));

            exec($cmd, $output);


            process_screenshoot($tmp_file_root.'_desktop_full_screenshot.jpeg','Fullscreen Thumbnail');

            process_screenshoot($tmp_file_root.'_desktop_full_screenshot.jpeg','Fullscreen');



            process_screenshoot($tmp_file_root.'_desktop_screenshot.jpeg','Desktop');
            process_screenshoot($tmp_file_root.'_mobile_screenshot.jpeg','Mobile');
            process_screenshoot($tmp_file_root.'_tablet_screenshot.jpeg','Tablet');



            unlink($tmp_file_root.'_desktop_full_screenshot.jpeg');
            unlink($tmp_file_root.'_desktop_screenshot.jpeg');
            unlink($tmp_file_root.'_mobile_screenshot.jpeg');
            unlink($tmp_file_root.'_tablet_screenshot.jpeg');



            exit;

        }
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}


function process_screenshoot($filename,$type){
    $size_data = getimagesize($filename);
    $width  = $size_data[0];
    $height = $size_data[1];

    $resized_image_filename=preg_replace('/original/','resize',$filename);

    switch ($type){
        case 'Fullscreen':
            $width_resize  = $width*.55;
            $height_resize =  $height*.55;
            break;
        case 'Fullscreen Thumbnail':

            $resized_image_filename=preg_replace('/original/','resize_thumbnail',$filename);
            $ratio=$width/$height;

            $width_resize  = 270;
            $height_resize =  270*$ratio;
            break;

        case 'Desktop':

            $ratio=$width/$height;

            $width_resize  = 270;
            $height_resize =  270*$ratio;
            break;
        case 'Tablet':

            $ratio=$width/$height;

            $width_resize  = 270;
            $height_resize =  270*$ratio;
            break;
        case 'Mobile':

            $ratio=$width/$height;
            $height_resize =  270;
            $width_resize  = 270/$ratio;

            break;
    }

    $image              = new ImageResize($filename);
    $image->quality_jpg = 100;
    $image->quality_png = 9;

    $image->resizeToBestFit($width_resize,$height_resize);


    $image->save($resized_image_filename);



    if (file_exists($resized_image_filename)) {
        usleep(  1000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  2000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  3000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  100000 );
    }



    $optimizerChain = OptimizerChainFactory::create();
    $optimizerChain->optimize($resized_image_filename);

    if (file_exists($resized_image_filename)) {
        usleep(  1000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  2000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  3000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  100000 );
    }





}


