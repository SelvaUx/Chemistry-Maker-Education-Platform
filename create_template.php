<?php
// create_template.php
// Helper to create a basic certificate template since we don't have an image asset
$width = 2000;
$height = 1414; // A4 Landscape roughly

$image = imagecreatetruecolor($width, $height);

// Colors
$white = imagecolorallocate($image, 255, 255, 255);
$gold = imagecolorallocate($image, 218, 165, 32);
$black = imagecolorallocate($image, 0, 0, 0);

// Fill background
imagefill($image, 0, 0, $white);

// Draw Border (Gold)
$borderThickness = 40;
for($i=0; $i<$borderThickness; $i++) {
    imagerectangle($image, $i, $i, $width-$i-1, $height-$i-1, $gold);
}

// Save
if(!is_dir('public_html/uploads/certificates/templates')) {
    mkdir('public_html/uploads/certificates/templates', 0777, true);
}

imagejpeg($image, 'public_html/uploads/certificates/templates/template_1.jpg', 90);
// Also save for template 2
imagejpeg($image, 'public_html/uploads/certificates/templates/template_2.jpg', 90);

imagedestroy($image);
echo "Templates created.";
?>
