<?php

function imageToAscii($imagePath, $width, $height) {

    $image = imagecreatefromstring(file_get_contents($imagePath));

    $resizedImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
    
    $characters = ' .,:;i1tfLCG08$#*@';

    $ascii = '';
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $color = imagecolorat($resizedImage, $x, $y);
            $rgb = imagecolorsforindex($resizedImage, $color);
            $brightness = ($rgb['red'] + $rgb['green'] + $rgb['blue']) / 3;
            $ascii .= $characters[(int)(($brightness / 255) * (strlen($characters) - 1))];
        }
        $ascii .= PHP_EOL;
    }
    
    imagedestroy($image);
    imagedestroy($resizedImage);
    
    return $ascii;
}

if (isset($_GET['img'])) {
    $imageUrl = $_GET['img'];
    
    $width = isset($_GET['width']) ? intval($_GET['width']) : 100;
    $height = isset($_GET['height']) ? intval($_GET['height']) : 50;
    
    $imageData = file_get_contents($imageUrl);
    $tmpImagePath = 'temp_img.jpg';
    file_put_contents($tmpImagePath, $imageData);
    $asciiArt = imageToAscii($tmpImagePath, $width, $height);
    unlink($tmpImagePath);
    
    echo '<html><head></head><body bgcolor="#000000" text="#FFFFFF"><pre>' . $asciiArt . '</pre><br><a href="index.html">make more</a></body></html>';
} else {
    echo 'no ?img= found.';
}
?>
