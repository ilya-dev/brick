<?php namespace Brick\Config;

use Brick\Utils\Filesystem as File;

class ConfigLoader {

    /**
     * The Filesystem instance.
     *
     * @var \Brick\Utils\Filesystem
     */
    protected $file;

    /**
     * The constructor.
     *
     * @param Brick\Utils\Filesystem|null $file
     * @return ConfigLoader
     */
    public function __construct(File $file = null)
    {
        $this->file = $file ?: new File;
    }

    /**
     * Load a raw configuration file.
     *
     * @return mixed
     */
    public function loadRaw()
    {
        $path = BRICK_ROOT_DIR.'/brick.json';

        return $this->file->read($path);
    }

    /**
     * Load a configuration file.
     *
     * @return array
     */
    public function load()
    {
        $raw = $this->loadRaw();

        $default = $this->file->requireFile(__DIR__.'/defaults.php');

        $custom = $raw ? \json_decode($raw, true) : [];

        return \array_merge($default, $custom);
    }

}

