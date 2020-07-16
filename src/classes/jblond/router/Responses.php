<?php

namespace jblond\router;

/**
 * Class responses
 * @package jblond\router
 */
class Responses
{

    /**
     * @param string $file file with path
     * @param string $name name for the client
     * @return void|int
     */
    public function download(string $file, string $name)
    {
        $this->headers(
            array(
                'Content-Transfer-Encoding' => 'Binary',
                'Content-Type' => 'application/octet-stream',
                'Content-disposition' => 'attachment; filename="' . $name . '"'
            )
        );
        readfile($file);
    }

    /**
     * @param string $type
     * @param string $value optional parameter
     * @return bool
     */
    public function header(string $type, string $value = ''): bool
    {
        if ($value !== '') {
            header("$type: $value");
            return true;
        }
        header("$type");
        return true;
    }

    /**
     * @param array $header_array Header => Value
     */
    public function headers(array $header_array): void
    {
        foreach ($header_array as $header => $value) {
            $this->header($header, $value);
        }
    }

    /**
     * @param string $location
     */
    public function redirect(string $location): void
    {
        header('Location: ' . $location, true, 302);
    }

    /**
     *
     */
    public function error404(): void
    {
        header("HTTP/1.1 404 Not Found");
    }

    /**
     *
     */
    public function error405(): void
    {
        header("HTTP/1.1 405 Method Not Allowed");
    }

    /**
     * send a 503 error to the client
     */
    public function error503(): void
    {
        $this->header('HTTP/1.1 503 Service Unavailable');
        $this->header('Status', '503 Service Unavailable');
    }
}
