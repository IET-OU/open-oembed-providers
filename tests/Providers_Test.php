<?php namespace IET_OU\Open_Oembed_Providers\Test;

use \IET_OU\SubClasses\SubClasses;


class Providers_Test extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        \IET_OU\Open_Media_Player\Base::$throw_no_framework = false;
        \IET_OU\SubClasses\SubClasses::$verbose = true;
    }

    public function testOembedProviders()
    {
        // Arrange
        $sub = new SubClasses();

        // Act
        $providers = $sub->get_oembed_providers();
        $unique = array_unique($providers);

        $num_providers = count($providers);
        $num_unique = count($unique);

        echo sprintf(">> oEmbed providers entries: %d / Unique: %d\n", $num_providers, $num_unique);

        // Assert
        $this->assertEquals(32, $num_providers);
        $this->assertEquals(16, $num_unique);
    }

}

