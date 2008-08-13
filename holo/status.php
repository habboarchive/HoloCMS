<?php 
Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Expiration Date 
Header("Content-type: image/png"); //image type 
$width=65; //change this if you must 
$height=15; //change if you want but id leave 
$ipval = $_GET['ip']; 
$portval = $_GET['port']; 
$ip = $ipval; //change to yourip 
$port = $portval; //change to server port 
$connectXD = @fsockopen($ip, $port, $errno, $errstr, 2); //connection through fsock 
if($connectXD){ //if its available 
//background color is white 
$c2r=255; 
$c2g=255; 
$c2b=255; 
//text is green 
$c1r=0; //red 
$c1g=125; //green 
$c1b=0; //blue 
$string = "Online"; //Online text :D 
}else{ //uh ohs!!! 
//background is white 
$c2r=255; //red 
$c2g=255; //green 
$c2b=255; //blue 
//text is red 
$c1r=255; //red 
$c1g=0; //green 
$c1b=0; //blue 
$string = "Offline"; //Offline text 
} //end else if for checking 

//Border color is greenish 
$c3r=255; //red 
$c3g=255; //green 
$c3b=255; //blue 

$pic=ImageCreate($width,$height); //create the image 
$col1=ImageColorAllocate($pic,$c1r,$c1g,$c1b); //text 
$col2=ImageColorAllocate($pic,$c2r,$c2g,$c2b); //background 
$col3=ImageColorAllocate($pic,$c3r,$c3g,$c3b); //border 
ImageFilledRectangle($pic, 0, 0, $width, 15, $col3); //create rectangle 
ImageFilledRectangle($pic, 0, 0, $width, 15, $col2); //Create teh rectangle for the text and stuff 
ImageString($pic, 10, 0, 0, $string, $col1); //this is text 
ImageColorTransparent($pic,$col2); 
ImagePNG($pic); //show image 
ImageDestroy($pic); //desolate it 
?>