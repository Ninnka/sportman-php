<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/31
 * Time: 15:46
 */
static $sportman_pic_prefix = "http://ok7pzw2ak.bkt.clouddn.com/";

function randString() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $result = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < 10; $i++) {
        $result .= $chars[rand(0, $max)];
    }
    return $result;
}
