<?php

declare(strict_types=1);

namespace BsbFlysystemTest\Adapter\Factory;

use BsbFlysystem\Adapter\Factory\FtpAdapterFactory;
use BsbFlysystemTest\Bootstrap;
use BsbFlysystemTest\Framework\TestCase;

class FtpAdapterFactoryTest extends TestCase
{
    /**
     * @var \ReflectionProperty
     */
    protected $property;

    /**
     * @var \ReflectionMethod
     */
    protected $method;

    public function setup()
    {
        $class          = new \ReflectionClass('BsbFlysystem\Adapter\Factory\FtpAdapterFactory');
        $this->property = $class->getProperty('options');
        $this->property->setAccessible(true);

        $this->method = $class->getMethod('validateConfig');
        $this->method->setAccessible(true);
    }

    public function testCreateService()
    {
        $sm      = Bootstrap::getServiceManager();
        $factory = new FtpAdapterFactory();

        $adapter = $factory($sm, 'ftp_default');

        $this->assertInstanceOf('League\Flysystem\Adapter\Ftp', $adapter);
    }

    /**
     * @dataProvider validateConfigProvider
     */
    public function testValidateConfig(
        $options,
        $expectedOptions = false,
        $expectedException = false,
        $expectedExceptionMessage = false
    ) {
        $factory = new FtpAdapterFactory($options);

        if ($expectedException) {
            $this->expectException($expectedException, $expectedExceptionMessage);
        }

        $this->method->invokeArgs($factory, []);

        if (is_array($expectedOptions)) {
            $this->assertEquals($expectedOptions, $this->property->getValue($factory));
        }
    }

    public function validateConfigProvider()
    {
        return [
            [
                [],
                false,
                'UnexpectedValueException',
                "Missing 'host' as option",
            ],
            [
                ['host' => 'foo'],
                false,
                'UnexpectedValueException',
                "Missing 'port' as option",
            ],
            [
                ['host' => 'foo', 'port' => 'foo'],
                false,
                'UnexpectedValueException',
                "Missing 'username' as option",
            ],
            [
                ['host' => 'foo', 'port' => 'foo', 'username' => 'foo'],
                false,
                'UnexpectedValueException',
                "Missing 'password' as option",
            ],
            [
                ['host' => 'foo', 'port' => 'foo', 'username' => 'foo', 'password' => 'foo'],
                ['host' => 'foo', 'port' => 'foo', 'username' => 'foo', 'password' => 'foo'],
            ],
        ];
    }
}
