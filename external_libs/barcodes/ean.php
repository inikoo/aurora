<?php

class EAN {
	private $_key;
	private $_checksum;
	private $_bars;

	private $_image;
	private $_width;
	private $_height;

	public function __construct($number, $scale=null) {


		$PARITY_KEY   = array(
			0 => "000000",
			1 => "001011",
			2 => "001101",
			3 => "001110",
			4 => "010011",
			5 => "011001",
			6 => "011100",
			7 => "010101",
			8 => "010110",
			9 => "011010"
		);

		$this->scale = (($scale==null || $scale<4) ? 4 : $scale);

		// if $number.length() < 12 || >= 13  error

		// Get the parity key, which is based on the first digit.
		
		
		
		
		$this->_key = $PARITY_KEY[substr($number, 0, 1)];

		// The checksum is appended to the 12 digit string
		$this->_checksum = $this->ean_checksum($number);
		$this->number = $number.$this->_checksum;


		$this->_bars = $this->_encode();



		$this->_createImage();
		$this->_drawBars();
		$this->_drawText();
	}


	/**
	 * The following incantations use the parity key (based off the
	 * first digit of the unencoded number) to encode the first six
	 * digits of the barcode. The last 6 use the same parity.
	 *
	 * So, if the key is 010101, the first digit (of the first six
	 * digits) uses odd parity encoding. The second uses even. The
	 * third uses odd, and so on.
	 */


	protected function _encode() {


		$LEFT_PARITY  =array(
			// Odd Encoding
			0 => array (
				0 => "0001101",
				1 => "0011001",
				2 => "0010011",
				3 => "0111101",
				4 => "0100011",
				5 => "0110001",
				6 => "0101111",
				7 => "0111011",
				8 => "0110111",
				9 => "0001011"
			),
			// Even Encoding
			1 => array (
				0 => "0100111",
				1 => "0110011",
				2 => "0011011",
				3 => "0100001",
				4 => "0011101",
				5 => "0111001",
				6 => "0000101",
				7 => "0010001",
				8 => "0001001",
				9 => "0010111"
			)
		);


		$RIGHT_PARITY = array(
			0 => "1110010",
			1 => "1100110",
			2 => "1101100",
			3 => "1000010",
			4 => "1011100",
			5 => "1001110",
			6 => "1010000",
			7 => "1000100",
			8 => "1001000",
			9 => "1110100"
		);
		$GUARD        = array(
			'start' => "101",
			'middle' => "01010",
			'end' => "101",
		);






		$barcode[] = $GUARD['start'];
		for ($i=1;$i<=strlen($this->number)-1;$i++) {



			if ($i<7)
				$barcode[] = $LEFT_PARITY[$this->_key[$i-1]][substr($this->number, $i, 1)];
			else
				$barcode[] = $RIGHT_PARITY[substr($this->number, $i, 1)];
			if ($i==6)
				$barcode[] = $GUARD['middle'];
		}
		$barcode[] = $GUARD['end'];



		return $barcode;
	}


	/**
	 * Create the image.
	 *
	 * The Height is 60 times the scale and the width is simply
	 * 180% of the height.
	 */
	protected function _createImage() {
		$this->_height = $this->scale*60;
		$this->_width  = 1.8*$this->_height;

		$this->_image = imagecreate($this->_width, $this->_height);
		$bg_color=imagecolorallocate($this->_image, 0xFF, 0xFF, 0xFF);
	}


	/**
	 * Draw the actual bars themselves.
	 *
	 * We have defined some constants. MAX is the y-value for the maximum
	 * height a bar should go. FLOOR is the y-value for the minimum height.
	 *
	 * The differences in margin for MAX and FLOOR are because most of the
	 * barcode doesn't extend to the bottom, only the guards do.
	 *
	 * WIDTH is the actual width of the bars.
	 *
	 * X is the starting position of the bars, which is a fifth of the way
	 * into the image.
	 *
	 * To draw the bars, we translate a binary string into bars:
	 *
	 * 10111001 - bar, empty, bar, bar, bar, empty, empty, bar
	 */
	protected function _drawBars() {
		$bar_color=imagecolorallocate($this->_image, 0x00, 0x00, 0x00);

		define("MAX", $this->_height*0.025);
		define("FLOOR", $this->_height*0.825);
		define("WIDTH", $this->scale);


		$x = ($this->_height*0.2)-WIDTH;


		foreach ($this->_bars as $bar) {

			$tall = 0;

			if (strlen($bar)==3 || strlen($bar)==5)
				$tall = ($this->_height*0.08);
			for ($i=1;$i<=strlen($bar);$i++) {


				if (substr($bar, $i-1, 1)==='1') {

					imagefilledrectangle($this->_image, $x, MAX, $x+WIDTH, FLOOR+$tall, $bar_color);
				}
				$x += WIDTH;
			}
		}

	}


	/**
	 * Draw the text:
	 *
	 * The first digit is left of the first guard. The kerning
	 * is how much space is in between the individual characters.
	 *
	 * We add kerning after the first character to skip over the
	 * first guard. Then we do it again after the 6th character
	 * to skip over the second guard.
	 *
	 * We don't need to skip over the last guard.
	 *
	 * The fontsize is 7 times the scale.
	 * X is the start point, which is .05 a way into the image
	 */
	protected function _drawText() {
		$x = $this->_width*0.05;
		$y = $this->_height*0.96;

		$text_color=imagecolorallocate($this->_image, 0x00, 0x00, 0x00);

		$font=dirname(__FILE__)."/"."FreeSansBold.ttf";
		$fontsize = $this->scale*(7);
		$kerning = $fontsize*1;

		for ($i=0;$i<strlen($this->number);$i++) {
			imagettftext($this->_image, $fontsize, 0, $x, $y, $text_color, $font, $this->number[$i]);
			if ($i==0 || $i==6)
				$x += $kerning*0.5;
			$x += $kerning;
		}
	}


	/**
	 * Send the headers and display the barcode.
	 *
	 * Destroy the image afterwards because we're firing and forgetting
	 */
	public function display() {
		header("Content-Type: image/png; name=\"barcode.png\"");
		imagepng($this->_image);
		imagedestroy($this->_image);
	}


	function ean_checksum($ean) {
		$ean=(string)$ean;
		$even=true; $esum=0; $osum=0;
		for ($i=strlen($ean)-1;$i>=0;$i--) {
			if ($even) $esum+=$ean[$i]; else $osum+=$ean[$i];
			$even=!$even;
		}
		return (10-((3*$esum+$osum)%10))%10;
	}


}
