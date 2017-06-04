<?php

function codeimg($width = 70, $height = 25, $len = 4, $border = FALSE)
{
    $code = ''; //create_captcha
    for ($i = 0; $i < $len; $i++) {
        $code .= dechex(mt_rand(0, 15));
    }

    $img = imagecreatetruecolor($width, $height);
    imagefill($img, 0, 0, imagecolorallocate($img, 255, 255, 255));

    for ($i = 0; $i < 6; $i++) { //干扰线
        $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imageline($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color);
    }

    for ($i = 0; $i < 100; $i++) { //背景
        $color = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        imagestring($img, 1, mt_rand(1, $width), mt_rand(1, $height), '*', $color);
    }

    for ($i = 0; $i < strlen($code); $i++) { //验证码
        $color = imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200));
        imagestring($img, 5, $i * $width / $len + mt_rand(1, 5), mt_rand(1, $height / 2), $code[$i], $color);
    }

    if ($border) { //黑色边框
        imagerectangle($img, 0, 0, $width - 1, $height - 1, imagecolorallocate($img, 0, 0, 0));
    }

    ob_start();
    imagepng($img);
    $result = ob_get_contents();
    ob_end_clean();

    imagedestroy($img);

    return ['status' => API_SUCCESS, 'message' => '成功', 'time' => time(), 'image' => base64_encode($result), 'code' => $code];
}