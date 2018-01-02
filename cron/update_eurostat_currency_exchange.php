<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 December 2017 at 21:55:29 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';

$filename='cron/ert_bil_eur_d.tsv';

$data = tsv_to_array($filename,array('header_row'=>true,'remove_header_row'=>true,'debug'=>false));

foreach($data as $key=>$row){
$index=0;
    foreach($row as $key=>$value){
           // print "$key $value\n";

            if($index==0){

                print $value."\n";
                $currency_code=preg_split('/,/',$value)[2];

                if($currency_code=='ALL'){
                    break;
                }

            }else{
                if(preg_match('/(\d{4})M(\d{2})D(\d{2})/',$key,$matches)){
                   $date=$matches[1].'-'.$matches[2].'-'.$matches[3];
                }

                if(is_numeric($value)){
                    $sql=sprintf('insert into kbase.`Eurostat Currency Exchange Dimension` (`Date`,`Currency Pair`,`Exchange`) values (%s,%s,%f)',prepare_mysql($date),prepare_mysql('EUR'.$currency_code),$value);
                    $db->exec($sql);
                   // print $sql."\n";


                    if($value!=0){
                        $sql=sprintf('insert into kbase.`Eurostat Currency Exchange Dimension` (`Date`,`Currency Pair`,`Exchange`) values (%s,%s,%f)',prepare_mysql($date),prepare_mysql($currency_code.'EUR'),$value);
                        $db->exec($sql);
                        //print $sql."\n";
                    }
                }

            }

            $index++;
    }


}

function tsv_to_array($file,$args=array()) {
    //key => default
    $fields = array(
        'header_row'=>true,
        'remove_header_row'=>true,
        'trim_headers'=>true, //trim whitespace around header row values
        'trim_values'=>true, //trim whitespace around all non-header row values
        'debug'=>false, //set to true while testing if you run into troubles
        'lb'=>"\n", //line break character
        'tab'=>"\t", //tab character
    );
    foreach ($fields as $key => $default) {
        if (array_key_exists($key,$args)) { $$key = $args[$key]; }
        else { $$key = $default; }
    }

    if (!file_exists($file)) {


        if ($debug) { $error = 'File does not exist: '.htmlspecialchars($file).'.'; }
        else { $error = "File does not exist: $file\n"; }
        exit($error);
    }

    if ($debug) { echo '<p>Opening '.htmlspecialchars($file).'&hellip;</p>'; }
    $data = array();

    if (($handle = fopen($file,'r')) !== false) {
        $contents = fread($handle, filesize($file));
        fclose($handle);
    } else {
        exit('There was an error opening the file.');
    }



    $lines = explode($lb,$contents);
    if ($debug) { echo '<p>Reading '.count($lines).' lines&hellip;</p>'; }

    $row = 0;
    foreach ($lines as $line) {
        $row++;
        if (($header_row) && ($row == 1)) { $data['headers'] = array(); }
        else { $data[$row] = array(); }
        $values = explode($tab,$line);
        foreach ($values as $c => $value) {
            if (($header_row) && ($row == 1)) { //if this is part of the header row
                if (in_array($value,$data['headers'])) { exit('There are duplicate values in the header row: '.htmlspecialchars($value).'.'); }
                else {
                    if ($trim_headers) { $value = trim($value); }
                    $data['headers'][$c] = $value.''; //the .'' makes sure it's a string
                }
            } elseif ($header_row) { //if this isn't part of the header row, but there is a header row
                $key = $data['headers'][$c];
                if ($trim_values) { $value = trim($value); }
                $data[$row][$key] = $value;
            } else { //if there's not a header row at all
                $data[$row][$c] = $value;
            }
        }
    }

    if ($remove_header_row) {
        unset($data['headers']);
    }

    if ($debug) { echo '<pre>'.print_r($data,true).'</pre>'; }
    return $data;
}



?>
