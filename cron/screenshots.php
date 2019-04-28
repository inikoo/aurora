<?php

require_once 'common.php';

try {
    $stmt = $db->prepare("SELECT `Page Key`,`Webpage URL` FROM sk.`Page Store Dimension`");
    $stmt->execute();


    //$stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach($stmt->fetchAll() as $k=>$v) {
        echo "Start Time :".date("Y-m-d  H:i:s", time())."\n";
        /*echo $v['Page Key']."|".$v['Webpage URL']."\n";*/
        exec("node cron/screenshots.js --pageKey=".$v['Page Key']." --url=\"".$v['Webpage URL']."\" &", $output);
        echo "End Time :".date("Y-m-d  H:i:s", time())."\n";
    }
    foreach($output as $k=>$v) {
        echo $k."|".$v."\n";

    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
