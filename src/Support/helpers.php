<?php

use Cart\Support\NormalizeData;
use Cart\Support\XssSecure;

if (!function_exists('xss_clean')) {
    function xss_clean($data)
    {
        return XssSecure::xss_clean($data);
    }
}

if (!function_exists('filter')) {
    function filter($data, $type)
    {
        return NormalizeData::filter($data, $type);
    }
}

function array_map_recursive(callable $func, array $array) {
    return filter_var($array, \FILTER_CALLBACK, ['options' => $func]);
}
