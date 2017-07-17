<?php namespace IET_OU\Open_Oembed_Providers\Test;

/**
 * Unit / integration tests for oEmbed provider classes.
 *
 * @copyright Copyright 2015 The Open University.
 */

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
        $plugins = new \IET_OU\Open_Media_Player\Plugin_Finder();

        // Act
        $providers = $plugins->get_oembed_providers();
        $unique = array_unique($providers);

        $num_providers = count($providers);
        $num_unique = count($unique);

        echo sprintf(">> oEmbed providers entries: %d / Unique: %d\n", $num_providers, $num_unique);

        // Assert
        $this->assertEquals(34, $num_providers);
        $this->assertEquals(17, $num_unique);
    }
}
