<?php
//SERVER

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class PHP_Stream_Server
{
    private $filePath;

    public function __construct($filePath)
    {
        if (!is_file($filePath))
            throw new Exception((string)$filePath . ' is not a file.');

        $this->filePath = $filePath;
        $this->stream();
    }

    private function stream()
    {
        $handle = fopen($this->filePath, 'r');
        $fileName = basename($this->filePath);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $this->filePath);

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
    }
}


