<?php
//////////////////////////////////////////////////////////////
///                                                         //
//    phPie() by James Heinrich <info@silisoftware.com>     //
//        available at http://www.silisoftware.com          //
//                                                         ///
//////////////////////////////////////////////////////////////
///                                                         //
//         This code is released under the GNU GPL:         //
//           http://www.gnu.org/copyleft/gpl.html           //
//                                                          //
//      +---------------------------------------------+     //
//      | If you do use this code somewhere, send me  |     //
//      | an email and tell me how/where you used it. |     //
//      |                                             |     //
//      | If you really like it, send me a postcard:  |     //
//      |   James Heinrich                            |     //
//      |   17 Scott Street                           |     //
//      |   Kingston, Ontario                         |     //
//      |   K7L 1L3                                   |     //
//      |   Canada                                    |     //
//      +---------------------------------------------+     //
//                                                         ///
//////////////////////////////////////////////////////////////
///                                                         //
// v1.0.4 - January 15, 2003                                //
//   * Modified gd_version() to handle PHP 4.3.0+'s bundled //
//     version of the GD library                            //
//   * Moved the plotting of the pie slice border color for //
//     GD v2.0+ to on top of the pie slice fill color - it  //
//     looks better that way.                               //
//   * Support for passing serialized data if               //
//     magic_quotes_gpc is off                              //
//                                                          //
// v1.0.3 - October 24, 2002                                //
//   * added SortData option which will, if set to FALSE,   //
//     disable sorting the data and plot all data in the    //
//     order it's supplied, and not combine very small      //
//     values into "Other"                                  //
//                                                          //
// v1.0.2 - October 23, 2002                                //
//   * prevent flood-filling incorrect areas when slices    //
//     are very small and GD < v2.0.1 is used               //
//     Thanks Jami Lowery <jami@ego-systems.com>            //
//                                                          //
// v1.0.1 - August 12, 2002                                 //
//   * Support for register_globals = off                   //
//                                                          //
// v1.0.0 - May 17, 2002                                    //
//   * initial public release                               //
//                                                         ///
//////////////////////////////////////////////////////////////

function gd_version() {
	if (version_compare(phpversion(), '4.3.0', '>=')) {
		return (float) 2;
	}
	ob_start();
	phpinfo();
	$buffer = ob_get_contents();
	ob_end_clean();
	preg_match('|<B>GD Version</B></td><TD ALIGN="left">([^<]*)</td>|i', $buffer, $matches);
	// return $matches[1]; // 1.6.2 or higher
	return (float) substr($matches[1], 0, 3); // 1.6
}

function FilledArc(&$im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $fill_color='none') {

	if (gd_version() >= 2.0) {

		if ($fill_color != 'none') {
			// fill
			ImageFilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $fill_color, IMG_ARC_PIE);
		}
		// outline
		ImageFilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, IMG_ARC_EDGED | IMG_ARC_NOFILL | IMG_ARC_PIE);

	} else {

		// cbriou@orange-art.fr

		// To draw the arc
		ImageArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color);

		// To close the arc with 2 lines between the center and the 2 limits of the arc
		$x = $CenterX + (cos(deg2rad($Start)) * ($DiameterX / 2));
		$y = $CenterY + (sin(deg2rad($Start)) * ($DiameterY / 2));
		ImageLine($im, $x, $y, $CenterX, $CenterY, $line_color);
		$x = $CenterX + (cos(deg2rad($End)) * ($DiameterX / 2));
		$y = $CenterY + (sin(deg2rad($End)) * ($DiameterY / 2));
		ImageLine($im, $x, $y, $CenterX, $CenterY, $line_color);

		if ($fill_color != 'none') {
			if (($End - $Start) > 0.5) {
				// ImageFillToBorder() will flood the wrong parts of the image if the slice is too small
				// thanks Jami Lowery <jami@ego-systems.com> for pointing out the problem

				// To fill the arc, the starting point is a point in the middle of the closed space
				$x = $CenterX + (cos(deg2rad(($Start + $End) / 2)) * ($DiameterX / 4));
				$y = $CenterY + (sin(deg2rad(($Start + $End) / 2)) * ($DiameterY / 4));
				ImageFillToBorder($im, $x, $y, $line_color, $fill_color);
			}
		}
	}
}

function phPie($data, $width, $height, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, $Legend, $FontNumber, $SortData=TRUE) {
	if ($im = @ImageCreate($width, $height)) {
		$background_color = ImageColorAllocate($im, hexdec(substr($BackgroundColor, 0, 2)), hexdec(substr($BackgroundColor, 2, 2)), hexdec(substr($BackgroundColor, 4, 2)));
		$line_color       = ImageColorAllocate($im, hexdec(substr($LineColor, 0, 2)), hexdec(substr($LineColor, 2, 2)), hexdec(substr($LineColor, 4, 2)));

		$fillcolorsarray = explode(';', $DisplayColors);
		for ($i = 0; $i < count($fillcolorsarray); $i++) {
			$fill_color[]  = ImageColorAllocate($im, hexdec(substr($fillcolorsarray["$i"], 0, 2)), hexdec(substr($fillcolorsarray["$i"], 2, 2)), hexdec(substr($fillcolorsarray["$i"], 4, 2)));
			$label_color[] = ImageColorAllocate($im, hexdec(substr($fillcolorsarray["$i"], 0, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 2, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 4, 2)) * 0.8);
		}

		$TotalArrayValues = array_sum($data);
		if ($SortData) {
			arsort($data);
		}
		$Start = 0;
		$valuecounter = 0;
		$ValuesSoFar = 0;
		foreach ($data as $key => $value) {
			$ValuesSoFar += $value;
			if (!$SortData || (($value / $TotalArrayValues) > $MinDisplayPct)) {
				$End = ceil(($ValuesSoFar / $TotalArrayValues) * 360);
				FilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $fill_color[$valuecounter % count($fill_color)]);
				if ($Legend) {
					ImageString($im, $FontNumber, 5, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), $key.' ('.number_format(($value / $TotalArrayValues) * 100, 1).'%)', $label_color[$valuecounter % count($label_color)]);
				}
				$Start = $End;
			} else {
				// too small to bother drawing - just finish off the arc with no fill and break
				$End = 360;
				if ((($TotalArrayValues - $ValuesSoFar) / $TotalArrayValues) > 0.0025) {
					// only fill in if more than 0.25%, otherwise colors might bleed
					FilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $line_color);
				}
				if ($Legend) {
					ImageString($im, $FontNumber, 5, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), 'Other ('.number_format((($TotalArrayValues - $ValuesSoFar) / $TotalArrayValues) * 100, 1).'%)', $line_color);
				}
				break;
			}
			$valuecounter++;
		}

		// display image
		if (ImageTypes() & IMG_PNG) {
			header('Content-type: image/png');
			ImagePNG($im);
		} else if (ImageTypes() & IMG_GIF) {
			header('Content-type: image/gif');
			ImageGIF($im);
		} else if (ImageTypes() & IMG_JPG) {
			header('Content-type: image/jpeg');
			ImageJPEG($im);
		}
		ImageDestroy($im);
		return TRUE;

	} else {

		 echo 'Cannot Initialize new GD image stream';
		 return FALSE;

	}
}


if (isset($_REQUEST['data'])) {

	if (is_array($_REQUEST['data'])) {

		// you can pass data to this file (via the GETstring for example) :
		// echo '<img src="phpie.php?data[male]=15&data[female]=25">';
		$data = $_REQUEST['data'];

	} else {

		// you can pass data to this file (via the GETstring for example) :
		// echo '<img src="phpie.php?data='.serialize($data).'">';
		if (get_magic_quotes_gpc()) {
			$data = unserialize(stripslashes($_REQUEST['data']));
		} else {
			$data = unserialize($_REQUEST['data']);
		}

	}

} else {

	if (isset($demomode)) {
		srand(time());
		for ($i = 1; $i < 8; $i++) {
			$data['SampleData'.$i] = rand(0, 1000);
		}
	} else {
		$data = array('NO DATA'=>1);
	}

}

$width           = (isset($_REQUEST['width'])           ? $_REQUEST['width']           : 500);
$height          = (isset($_REQUEST['height'])          ? $_REQUEST['height']          : 300);
$CenterX         = (isset($_REQUEST['CenterX'])         ? $_REQUEST['CenterX']         : round($width / 2));
$CenterY         = (isset($_REQUEST['CenterY'])         ? $_REQUEST['CenterY']         : round($height / 2));
$DiameterX       = (isset($_REQUEST['DiameterX'])       ? $_REQUEST['DiameterX']       : round($width * 0.95));
$DiameterY       = (isset($_REQUEST['DiameterY'])       ? $_REQUEST['DiameterY']       : round($height * 0.95));
$MinDisplayPct   = (isset($_REQUEST['MinDisplayPct'])   ? $_REQUEST['MinDisplayPct']   : 0.01);
$DisplayColors   = (isset($_REQUEST['DisplayColors'])   ? $_REQUEST['DisplayColors']   : '3399FF;FF9933;FF0000;66CC00;FF33FF;00FFFF;9933FF;EECC33;33FF33');
$BackgroundColor = (isset($_REQUEST['BackgroundColor']) ? $_REQUEST['BackgroundColor'] : 'CCCCCC');
$LineColor       = (isset($_REQUEST['LineColor'])       ? $_REQUEST['LineColor']       : '000000');
$Legend          = (isset($_REQUEST['Legend'])          ? (bool) $_REQUEST['Legend']   : TRUE);
$FontNumber      = (isset($_REQUEST['FontNumber'])      ? $_REQUEST['FontNumber']      : 3);
$SortData        = (isset($_REQUEST['SortData'])        ? (bool) $_REQUEST['SortData'] : TRUE);
if ($Legend) {
	$DiameterX = $DiameterY;
	$CenterX   = $width - $CenterY;
}
if (($width > 8192) || ($height > 8192) || ($width <= 0) || ($height <= 0)) {
	die('Image size limited to 8192 x 8192 for safety reasons');
}

phPie($data, $width, $height, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, $Legend, $FontNumber, $SortData);

?>