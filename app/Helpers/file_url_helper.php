<?php

if (!function_exists('fileUrl')) {
    function fileUrl($path)
    {
        // Return the full URL including the path to the image
        return env('FILE_APP_URL') . '/' . env('FILE_APP_FOLDER') . '/' . $path;
    }
}