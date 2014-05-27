<?php

require_once'common.php';

include 'external_libs/securimage/securimage.php';

$img = new securimage();

if(isset($_REQUEST['height']) and is_numeric($_REQUEST['height']))
$height=$_REQUEST['height'];
else
$height=50;

$img->perturbation = 0.5; // 1.0 = high distortion, higher numbers = more distortion
$img->image_bg_color = new Securimage_Color("#DDDDDD");
$img->text_color = new Securimage_Color("#000000");

$img->line_color = new Securimage_Color("#222222");
$img->image_height = $height;
$img->image_width = (int)($img->image_height * 2.875);
$img->code_length = 3;
$img->num_lines = rand(1, 2);
$img->show(); 