<?php
use \Gumlet\ImageResize;
use Spatie\ImageOptimizer\OptimizerChainFactory;
require_once 'common.php';
$optimizerChain = OptimizerChainFactory::create();


$sql=sprintf('SELECT `Page Key`,`Webpage URL` FROM `Page Store Dimension` where  `Webpage State`="Online" and `Page Key`= 469  ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {

		    $webpage=get_object('Webpage',$row['Page Key']);

            $tmp_file_root=sprintf('server_files/tmp/original_%d_%d',gmdate('U'),$row['Page Key']);

            $cmd=sprintf('node cron/screenshots.js --file_root="%s" --url="%s" &',$tmp_file_root,addslashes($row['Webpage URL']));

            exec($cmd, $output);


            process_screenshot($webpage,$tmp_file_root.'_desktop_full_screenshot.jpeg','Full Webpage Thumbnail');
            process_screenshot($webpage,$tmp_file_root.'_desktop_full_screenshot.jpeg','Full Webpage');

            process_screenshot($webpage,$tmp_file_root.'_desktop_screenshot.jpeg','Desktop');
            process_screenshot($webpage,$tmp_file_root.'_mobile_screenshot.jpeg','Mobile');
            process_screenshot($webpage,$tmp_file_root.'_tablet_screenshot.jpeg','Tablet');




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


