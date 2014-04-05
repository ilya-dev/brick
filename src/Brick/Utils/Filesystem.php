<?php namespace Brick\Utils;

class Filesystem {

    /**
     * Read a file
     *
     * @param  string $file
     * @return mixed
     */
    public function read($file)
    {
        return file_get_contents($file);
    }

    /**
     * Require a .php file
     *
     * @param  string $file
     * @return mixed
     */
    public function requireFile($file)
    {
        return require $file;
    }

    /**
     * Append to a file
     *
     * @param  string  $file
     * @param  mixed   $content
     * @return integer
     */
    public function append($file, $content)
    {
        return file_put_contents($file, $content, FILE_APPEND);
    }

}

