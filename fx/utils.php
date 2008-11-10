<?php

/* Returns a subset of $source associative array containing elements with keys
 * listed in $fields array. If provided, $default is the default state of
 * the returned array, which is updated by the selected fields. */
function array_subset($source, $fields, $default=array())
{
    $result = $default;
    foreach ($fields as $f)
        $result[$f] = $source[$f];

    return $result;
}

/* Returns true if $array has all the keys listed in $fields, false otherwise. */
function array_require($array, $fields)
{
    foreach ($fields as $f) {
        if (!isset($array[$f])) return false;
        if ($array[$f] == "") return false;
    }
    return true;
}

/* Sets cookie named $cookie to $value */
function cookie_remember($cookie, $value)
{
    setcookie($cookie, $value, 0, '/');
}

/* Returns cookie named $cookie if available, otherwise returns null */
function cookie_recall($cookie)
{
    if (!isset($_COOKIE[$cookie])) return null;
    return $_COOKIE[$cookie];
}

/* Forgets the cookie named $cookie */
function cookie_forget($cookie)
{
    setcookie($cookie, false, 0, '/');
}

/* Copy picture uploaded as $field to $fname, and optionally resize it to
 * new dimensions ($new_w x $new_h), keeping aspect ratio. If preserving
 * aspect ratio, set the picture background to ($bg_r, $bg_g, $bg_b) value. */
function picture_upload_and_resize($field, $fname, $new_w = 0, $new_h = 0,
    $bg_r=255, $bg_g=255, $bg_b=255)
{
    if (!$_FILES[$field]) return false;

    $from = $_FILES[$field]['tmp_name'];
    $orig = $_FILES[$field]['name'];
    if (!$from) return false;

    list ($w, $h) = getimagesize($from);
    if (!$w or !$h) return false;

    if (!$new_w) {
        // samo kopiraj kako je
        $new_w = $w;
        $new_h = $h;
        $dst_x = 0;
        $dst_w = $w;
        $dst_y = 0;
        $dst_h = $h;
    } else {
        $dst_h = $new_w * $h / $w;
        if ($dst_h > $new_h) {
            // uzmi visinu kao mjerodavnu
            $dst_y = 0;
            $dst_h = $new_h;
            $dst_w = $new_h * $w / $h;
            $dst_x = ($new_w - $dst_w) / 2;
        } else {
            // uzmi sirinu kao mjerodavnu
            $dst_x = 0;
            $dst_w = $new_w;

            $dst_y = ($new_h - $dst_h) / 2;
        }
    }
    
    $img = false;
    if (stristr($orig, '.jpeg') or stristr($orig, '.jpg')) {
        $img = @imagecreatefromjpeg($from);
    } elseif (stristr($orig, '.gif')) {
        $img = @imagecreatefromgif($from);
    } elseif (stristr($orig, '.png')) {
        $img = @imagecreatefrompng($from);
    } else {
        $img = @imagecreatefromjpeg($from);
    }
    if (!$img) return false;

    $newimg = imagecreatetruecolor($new_w, $new_h);
    imagefill($newimg, 0, 0, imagecolorallocate($newimg, 218, 243, 250));
    // imagecopyresampled($newimg, $img, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
    // imagecopyresampled($newimg, $img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $w, $h);
    imagecopyresized($newimg, $img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $w, $h);
    imagejpeg($newimg, $fname); 

    return true;
}

?>
