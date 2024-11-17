<?php

declare(strict_types=1);

namespace LaminasTest\Config;

use Laminas\Config\Exception;
use Laminas\Config\Reader;
use Laminas\Config\StandaloneReaderPluginManager;
use PHPUnit\Framework\TestCase;

use function sprintf;

class StandaloneReaderPluginManagerTest extends TestCase
{
    /**
     * @return string[][]
     */
    public static function supportedConfigExtensions()
    {
        return [
            'ini'            => ['ini', Reader\Ini::class],
            'INI'            => ['INI', Reader\Ini::class],
            'json'           => ['json', Reader\Json::class],
            'JSON'           => ['JSON', Reader\Json::class],
            'xml'            => ['xml', Reader\Xml::class],
            'XML'            => ['XML', Reader\Xml::class],
            'yaml'           => ['yaml', Reader\Yaml::class],
            'YAML'           => ['YAML', Reader\Yaml::class],
            'javaproperties' => ['javaproperties', Reader\JavaProperties::class],
            'javaProperties' => ['javaProperties', Reader\JavaProperties::class],
            'JAVAPROPERTIES' => ['JAVAPROPERTIES', Reader\JavaProperties::class],
        ];
    }

    /**
     * @dataProvider supportedConfigExtensions
     * @param string $extension Configuration file extension.
     * @param string $expectedType Expected plugin class.
     */
    public function testCanRetrieveReaderByExtension($extension, $expectedType)
    {
        $manager = new StandaloneReaderPluginManager();
        self::assertTrue(
            $manager->has($extension),
            sprintf('Failed to assert plugin manager has plugin %s', $extension)
        );

        $plugin = $manager->get($extension);
        self::assertInstanceOf($expectedType, $plugin);
    }

    /**
     * @return string[][]
     */
    public static function supportedConfigClassNames()
    {
        return [
            Reader\Ini::class            => [Reader\Ini::class],
            Reader\Json::class           => [Reader\Json::class],
            Reader\Xml::class            => [Reader\Xml::class],
            Reader\Yaml::class           => [Reader\Yaml::class],
            Reader\JavaProperties::class => [Reader\JavaProperties::class],
        ];
    }

    /**
     * @dataProvider supportedConfigClassNames
     * @param string $class Plugin class to retrieve and expect.
     */
    public function testCanRetrieveReaderByPluginClassName($class)
    {
        $manager = new StandaloneReaderPluginManager();
        self::assertTrue(
            $manager->has($class),
            sprintf('Failed to assert plugin manager has plugin %s', $class)
        );

        $plugin = $manager->get($class);
        self::assertInstanceOf($class, $plugin);
    }

    public function testGetThrowsExceptionIfPluginNotFound()
    {
        $manager = new StandaloneReaderPluginManager();
        $this->expectException(Exception\PluginNotFoundException::class);
        $this->expectExceptionMessage('Config reader plugin by name bogus not found');
        $manager->get('bogus');
    }
}
