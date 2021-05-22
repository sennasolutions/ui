<?php

function default_input_chrome($size = "lg") {
    $sizeClass = "px-4 py-2.5";
    switch($size) {
        case "sm":
            $sizeClass = "px-3 py-2 text-sm";
            break;
        case "lg":
            $sizeClass = "px-4 py-2.5";
                break;
        case "xl":
            $sizeClass = "px-5 py-3 text-lg";
            break;
    }
  
    return "$sizeClass transition duration-50 ease-in-out
    block w-full  text-gray-700 bg-white border border-gray-300 rounded-md
    dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:focus:border-primary-color
    focus:ring focus:ring-primary-color-20 focus:border-primary-color-50
    placeholder-gray-400 focus:placeholder-gray-300";
}

