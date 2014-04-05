<?php namespace Brick\Config;

use Brick\Utils\Filesystem as File;

class ConfigLoader {

    /**
     * Filesystem instance
     *
     * @var Brick\Utils\Filesystem
     */
    protected $file;

    /**
     * The constructor
     *
     * @param  Brick\Utils\Filesystem|null $file
     * @return void
     */
    public function __construct(File $file = null)
    {
        $this->file = $file ?: new File;
    }

    /**
     * Load the raw configuration file
     *
     * @return null|string
     */
    public function loadRaw()
    {
        $path = BRICK_ROOT_DIR.'/brick.json';

        return $this->file->read($path);
    }

    /**
     * Load the configuration file
     *
     * @return array
     */
    public function load()
    {
        $raw     = $this->loadRaw();
        $default = $this->file->requireFile(__DIR__.'/defaults.php');

        $custom  = $raw ? json_decode($raw, true) : [];

        return array_merge($default, $custom);
    }

}

