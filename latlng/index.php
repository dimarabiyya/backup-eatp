<?php
// --------------------------------------------------
// GET PARAMETERS (OLD PHP SAFE)
// --------------------------------------------------
$filename = isset($_GET['filename']) ? $_GET['filename'] : '';
$lat      = isset($_GET['lat']) ? $_GET['lat'] : '';
$lng      = isset($_GET['lng']) ? $_GET['lng'] : '';
$date     = isset($_GET['date']) ? $_GET['date'] : '';

if ($filename == '') {
    header('HTTP/1.1 400 Bad Request');
    exit('Missing filename');
}

// --------------------------------------------------
// IMAGE INFO
// --------------------------------------------------
$imageInfo = getimagesize($filename);
if ($imageInfo === false) {
    header('HTTP/1.1 400 Bad Request');
    exit('Invalid image');
}

$width  = $imageInfo[0];
$height = $imageInfo[1];
$type   = $imageInfo[2];

// Only JPEG supported
if ($type != IMAGETYPE_JPEG) {
    header('HTTP/1.1 415 Unsupported Media Type');
    exit('Only JPEG images are supported');
}

// --------------------------------------------------
// LOAD ORIGINAL IMAGE
// --------------------------------------------------
$src = imagecreatefromjpeg($filename);

// --------------------------------------------------
// RESIZE IF IMAGE TOO LARGE (CRITICAL)
// --------------------------------------------------
$maxWidth = 2000;

if ($width > $maxWidth) {
    $ratio     = $maxWidth / $width;
    $newWidth  = $maxWidth;
    $newHeight = (int)($height * $ratio);

    $img = imagecreatetruecolor($newWidth, $newHeight);

    imagecopyresampled(
        $img,
        $src,
        0, 0, 0, 0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );

    imagedestroy($src); // FREE MEMORY

    $width  = $newWidth;
    $height = $newHeight;
} else {
    $img = $src;
}

// --------------------------------------------------
// FONT SIZE BASED ON IMAGE WIDTH
// --------------------------------------------------
if ($width >= 2000) {
    $fontSize = 50;
} elseif ($width >= 1000) {
    $fontSize = 30;
} else {
    $fontSize = 14;
}

// --------------------------------------------------
// TEXT TO PRINT
// --------------------------------------------------
$text = "Latitude: " . $lat . "\nLongitude: " . $lng . "\nDate: " . $date;

// --------------------------------------------------
// FONT & COLORS
// --------------------------------------------------
$fontFile  = 'C:/Windows/Fonts/mvboli.ttf';
$fontColor = imagecolorallocate($img, 255, 51, 0);
$glowColor = imagecolorallocate($img, 255, 255, 255);

// --------------------------------------------------
// TEXT POSITION
// --------------------------------------------------
$posX  = 20;
$posY  = 60;
$angle = 0;

// --------------------------------------------------
// DRAW TEXT (GLOW + TEXT)
// --------------------------------------------------
imagettftextblur($img, $fontSize, $angle, $posX + 1, $posY + 1, $glowColor, $fontFile, $text, 1);
imagettftextblur($img, $fontSize, $angle, $posX,     $posY,     $fontColor, $fontFile, $text);

// --------------------------------------------------
// OUTPUT IMAGE
// --------------------------------------------------
header('Content-Type: image/jpeg');
imagejpeg($img, null, 85);

// --------------------------------------------------
// FREE MEMORY
// --------------------------------------------------
imagedestroy($img);
exit;


// ==================================================
// BLUR TEXT FUNCTION (SAFE FOR OLD PHP)
// ==================================================
function imagettftextblur(
    &$image,
    $size,
    $angle,
    $x,
    $y,
    $color,
    $fontfile,
    $text,
    $blur_intensity = 0,
    $blur_filter = IMG_FILTER_GAUSSIAN_BLUR
) {
    if (is_int($blur_intensity) && $blur_intensity > 0) {

        $temp = imagecreatetruecolor(imagesx($image), imagesy($image));

        imagefill(
            $temp,
            0,
            0,
            imagecolorallocate($temp, 0, 0, 0)
        );

        imagettftext(
            $temp,
            $size,
            $angle,
            $x,
            $y,
            imagecolorallocate($temp, 255, 255, 255),
            $fontfile,
            $text
        );

        for ($i = 0; $i < $blur_intensity; $i++) {
            imagefilter($temp, $blur_filter);
        }

        for ($px = 0; $px < imagesx($temp); $px++) {
            for ($py = 0; $py < imagesy($temp); $py++) {
                $alpha = imagecolorat($temp, $px, $py) & 0xFF;
                if ($alpha > 0) {
                    imagesetpixel(
                        $image,
                        $px,
                        $py,
                        imagecolorallocatealpha(
                            $image,
                            ($color >> 16) & 0xFF,
                            ($color >> 8) & 0xFF,
                            $color & 0xFF,
                            127 - $alpha
                        )
                    );
                }
            }
        }

        imagedestroy($temp);
    } else {
        imagettftext($image, $size, $angle, $x, $y, $color, $fontfile, $text);
    }
}
