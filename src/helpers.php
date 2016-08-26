<?php

/**
 * Create a string from the values of an array
 *
 * @param $array
 * @return string
 */
function stringfy_array(array $array){
    return '\'' . implode('\', \'', $array) . '\'';
}