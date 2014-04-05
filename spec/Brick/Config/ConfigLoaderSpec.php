<?php namespace spec\Brick\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Brick\Utils\Filesystem as File;

class ConfigLoaderSpec extends ObjectBehavior {

    function let(File $file)
    {
        if ( ! defined($name = 'BRICK_ROOT_DIR')) define($name, 'foo');

        $this->beConstructedWith($file);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Brick\Config\ConfigLoader');
    }

    function it_loads_raw_configuration_file(File $file)
    {
        $file->read(Argument::any())->willReturn('bar');

        $this->loadRaw()->shouldReturn('bar');
    }

    function it_loads_configuration_file(File $file)
    {
        $file->requireFile(Argument::any())->willReturn([
            'doge' => 'wow',
            'foo'  => 'bar',
        ]);

        $file->read(Argument::any())->willReturn('{"doge": "such"}');

        $this->load()->shouldReturn([
            'doge' => 'such',
            'foo'  => 'bar',
        ]);
    }

}

