<?php
//SERVER

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'helper.php';

$path = __DIR__ . '/repo/8k.zip';

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
    if (ob_get_length())
    {
        flush();
        ob_flush();
    }

    $offset += $chunk;
}

// $stream = fgets($handle);

// //echo($stream);
// echo ftell($handle);
// echo PHP_EOL;

// $stream = fgets($handle);

// //echo($stream);
// echo ftell($handle);
// echo PHP_EOL;