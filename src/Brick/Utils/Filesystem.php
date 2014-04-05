<?php namespace Brick\Utils;

class Filesystem {

    /**
     * Read a given file
     *
     * @param  string $file
     * @return mixed
     */
    public function read($file)
    {
        return file_get_contents($file);
    }

    /**
     * Require a given .php file
     *
     * @param  string $file
     * @return mixed
     */
    public function requireFile($file)
    {
        return require $file;
    }

}

