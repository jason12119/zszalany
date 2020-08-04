<?php

header("Content-type: image/jpeg");

$file = "../upload/produkty/" . $_GET['id'] . "";


list(, , $type) = getimagesize($file);
switch ($type) {
	case "1":
		$image = imagecreatefromgif($file);
		break;
	case "2":
		$image = imagecreatefromjpeg($file);
		break;
	case "3":
		$image = imagecreatefrompng($file);
		break;
}

$img_podklad = imagecreatefrompng("../img/web/podklad.png");

$imageWidth = $_GET['width'];
$imageHeight = $_GET['width'];


$velxx = imagesx($image);

$velyy = imagesy($image);


$sirka = $velxx;
$vyska = $velyy;


if ($imageHeight == $imageWidth) {
	if ($sirka > $vyska) {

		$pomer_y = $vyska / $imageHeight;
		$pomer_x = $sirka / $imageWidth;
		$SIRKA = $sirka / $pomer_y;
		$VYSKA = $vyska / $pomer_x;
		$x = ($SIRKA - $imageWidth) / 2;
		$cil = @imagecreatetruecolor($imageWidth, $imageHeight);
		$cil = @imagecreatetruecolor($imageWidth, $imageHeight);
		$img_podklad = imagecreatefrompng("../img/web/podklad.png");
		imagecopy($cil, $img_podklad, 0, 0, 0, 0, $imageWidth, $imageHeight); //Nakopirujeme pozad� do obrazku
		imagecopyresampled($cil, $image, 0, ($imageHeight - $VYSKA) / 2, 0, 0, $imageWidth, $VYSKA, $sirka, $vyska);


	}
	if ($sirka < $vyska) {

		$pomer_x = $sirka / $imageWidth;
		$pomer_y = $vyska / $imageHeight;
		$VYSKA = $vyska / $pomer_x;
		$SIRKA = $sirka / $pomer_y;
		$y = ($VYSKA - $imageHeight) / 2;
		$x = ($SIRKA - $imageWidth) / 2;
		//echo $VYSKA ;
		$cil = @imagecreatetruecolor($imageWidth, $imageHeight);
		$img_podklad = imagecreatefrompng("../img/web/podklad.png");
		imagecopy($cil, $img_podklad, 0, 0, 0, 0, $imageWidth, $imageHeight); //Nakopirujeme pozad� do obrazku
		imagecopyresampled($cil, $image, ($imageWidth - $SIRKA) / 2, 0, 0, 0, $SIRKA, $imageHeight, $sirka, $vyska);

	}

	if ($sirka == $vyska) {

		$pomer_x = $sirka / $imageWidth;
		$pomer_y = $vyska / $imageHeight;
		$VYSKA = $vyska / $pomer_x;
		$SIRKA = $sirka / $pomer_y;
		$y = ($VYSKA - $imageHeight) / 2;
		$x = ($SIRKA - $imageWidth) / 2;
		//echo $VYSKA ;
		$cil = @imagecreatetruecolor($imageWidth, $imageHeight);
		$img_podklad = imagecreatefrompng("../img/web/podklad.png");
		imagecopy($cil, $img_podklad, 0, 0, 0, 0, $imageWidth, $imageHeight); //Nakopirujeme pozad� do obrazku
		imagecopyresampled($cil, $image, ($imageWidth - $SIRKA) / 2, 0, 0, 0, $SIRKA, $imageHeight, $sirka, $vyska);

	}
} else {
	if ($sirka >= $vyska) {

		$pomer_x = $sirka / $imageWidth;
		$pomer_y = $vyska / $imageHeight;
		$VYSKA = $vyska / $pomer_x;
		$SIRKA = $sirka / $pomer_y;
		$y = ($VYSKA - $imageHeight) / 2;
		$x = ($SIRKA - $imageWidth) / 2;
		//echo $VYSKA ;
		$cil = @imagecreatetruecolor($imageWidth, $imageHeight);
		$img_podklad = imagecreatefrompng("../img/web/podklad.png");
		imagecopy($cil, $img_podklad, 0, 0, 0, 0, $imageWidth, $imageHeight); //Nakopirujeme pozad� do obrazku
		imagecopyresampled($cil, $image, ($imageWidth - $SIRKA) / 2, 0, 0, 0, $SIRKA, $imageHeight, $sirka, $vyska);


	}
	if ($sirka < $vyska) {

		$pomer_x = $sirka / $imageWidth;
		$pomer_y = $vyska / $imageHeight;
		$VYSKA = $vyska / $pomer_x;
		$SIRKA = $sirka / $pomer_y;
		$y = ($VYSKA - $imageHeight) / 2;
		$x = ($SIRKA - $imageWidth) / 2;
		//echo $VYSKA ;
		$cil = @imagecreatetruecolor($imageWidth, $imageHeight);
		$img_podklad = imagecreatefrompng("../img/web/podklad.png");
		imagecopy($cil, $img_podklad, 0, 0, 0, 0, $imageWidth, $imageHeight); //Nakopirujeme pozad� do obrazku
		imagecopyresampled($cil, $image, ($imageWidth - $SIRKA) / 2, 0, 0, 0, $SIRKA, $imageHeight, $sirka, $vyska);

	}
}


imagejpeg($cil, NULL, 100);
imagedestroy($image);
imagedestroy($cil);


?>