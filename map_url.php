<?
/*
file: map_url.php
returns the url of the map
 */

require_once 'common.php';
require_once 'classes/Product.php';

function get_map_url($tipo){
  switch($tipo){
  default:
    return "http://chart.apis.google.com/chart?cht=p3&chd=t:60,40&chs=250x100&chl=Hello|World";
  }

}


