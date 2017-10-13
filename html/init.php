<?php
$postdata = file_get_contents("php://input");

$postcodeJSON = file_get_contents("https://api.postcodes.io/postcodes/" . $postdata);

$postcodeDetails = json_decode($postcodeJSON);

$lat = $postcodeDetails->result->latitude;
$long = $postcodeDetails->result->longitude;

//echo $lat;
//echo $long;

$latr = ($lat * pi() )/180;

//Code for lat long to tile location conversion
$zoom = 13;
$n = 2 ** $zoom;

$xraw = $n * (($long + 180) / 360);
$yraw = $n * (1 - (log(tan($latr) + (1/cos($latr))) / pi())) / 2;

$xtile = round($xraw);
$ytile = round($yraw);

//These lines calculate the offset caused by rounding the co-ordinates of the target postcode to a whole tile (in degrees)
$xoffset = ($xraw - $xtile) * 0.0446;
$yoffset = ($yraw - $ytile) * 0.027;


//$xlocs = array($xtile - 2, $xtile - 1, $xtile, $xtile + 1, $xtile + 2);
$xlocs = array($xtile - 3, $xtile - 2, $xtile - 1, $xtile, $xtile + 1);
//$ylocs = array($ytile - 1, $ytile, $ytile + 1);
$ylocs = array($ytile - 2, $ytile - 1, $ytile);

$time = time();

$pngnames = array();

foreach ($xlocs as $xvalue) {
  $url = "http://tile.openstreetmap.org/13/" . $xvalue . "/" . $ylocs[0] . ".png";
  copy($url, $time.$xvalue.$ylocs[0].".png");
  array_push($pngnames,$time.$xvalue.$ylocs[0].".png");
}

foreach ($xlocs as $xvalue) {
  $url = "http://tile.openstreetmap.org/13/" . $xvalue . "/" . $ylocs[1] . ".png";
  copy($url, $time.$xvalue.$ylocs[1].".png");
  array_push($pngnames,$time.$xvalue.$ylocs[1].".png");
}

foreach ($xlocs as $xvalue) {
  $url = "http://tile.openstreetmap.org/13/" . $xvalue . "/" . $ylocs[2] . ".png";
  copy($url, $time.$xvalue.$ylocs[2].".png");
  array_push($pngnames,$time.$xvalue.$ylocs[2].".png");
}

array_push($pngnames,$lat);
array_push($pngnames,$long);

array_push($pngnames,$xoffset);
array_push($pngnames,$yoffset);

echo json_encode(array_values($pngnames));

?>
