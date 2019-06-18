<?php
use \Gumlet\ImageResize;
use Spatie\ImageOptimizer\OptimizerChainFactory;
require_once 'common.php';
$optimizerChain = OptimizerChainFactory::create();


$sql=sprintf('SELECT `Page Key`,`Webpage URL` FROM `Page Store Dimension` where  `Webpage State`="Online" ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {


            $tmp_file_root=sprintf('server_files/tmp/%d_%d',gmdate('U'),$row['Page Key']);

            $cmd=sprintf('node cron/screenshots.js --file_root="%s" --url="%s" &',$tmp_file_root,addslashes($row['Webpage URL']));

            exec($cmd, $output);


            $optimizerChain->optimize($tmp_file_root.'_desktop_full_screenshot.jpeg',$tmp_file_root.'x_desktop_full_screenshot.jpeg');
            $optimizerChain->optimize($tmp_file_root.'_desktop_screenshot.jpeg',$tmp_file_root.'x_desktop_screenshot.jpeg');
            $optimizerChain->optimize($tmp_file_root.'_mobile_screenshot.jpeg',$tmp_file_root.'x_mobile_screenshot.jpeg');
            $optimizerChain->optimize($tmp_file_root.'_tablet_screenshot.jpeg',$tmp_file_root.'x_tablet_screenshot.jpeg');



            exit;

        }
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}


exit;

try {

    $stmt = $db->prepare("SELECT `Page Key`,`Webpage URL` FROM sk.`Page Store Dimension`");
    $stmt->execute();


    //$stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach($stmt->fetchAll() as $k=>$v) {
       // echo "Start Time :".date("Y-m-d  H:i:s", time())."\n";
        /*echo $v['Page Key']."|".$v['Webpage URL']."\n";*/
        exec("node cron/screenshots.js --pageKey=".$v['Page Key']." --url=\"".$v['Webpage URL']."\" &", $output);
        //echo "End Time :".date("Y-m-d  H:i:s", time())."\n";




        $optimizerChain->optimize('server_files/tmp/'.$v['Page Key'].'_desktop_full_screenshot.jpeg');
        $optimizerChain->optimize('server_files/tmp/'.$v['Page Key'].'_desktop_screenshot.jpeg');
        $optimizerChain->optimize('server_files/tmp/'.$v['Page Key'].'_mobile_screenshot.jpeg');
        $optimizerChain->optimize('server_files/tmp/'.$v['Page Key'].'_tablet_screenshot.jpeg');



    }
//    foreach($output as $k=>$v) {
//        echo $k."|".$v."\n";

//    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
