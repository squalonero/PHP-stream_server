<?php

function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function streamFile($path)
{

    $handle = fopen($path, 'r');
    $fileName = basename($path);

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $path);

    $size = fstat($handle)['size'];
    $offset = 0;

    stream_get_contents($handle, $offset);
    header("Content-Type: $mime_type");
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('FileName:' . $fileName);


    while ($offset < $size)
    {
        $chunk = min($size - $offset, 1024 * 8);
        //header("Content-Length: $chunk");

        echo fread($handle, $chunk);
        flush();
        ob_flush();
        $offset += $chunk;
    }
}
