<?php

declare(strict_types=1);

namespace BsbFlysystem\Adapter\Factory;

use BsbFlysystem\Exception\RequirementsException;
use BsbFlysystem\Exception\UnexpectedValueException;
use League\Flysystem\Sftp\SftpAdapter as Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;

class SftpAdapterFactory extends AbstractAdapterFactory
{
    /**
     * {@inheritdoc}
     */
    public function doCreateService(ServiceLocatorInterface $serviceLocator)
    {
        if (! class_exists(\League\Flysystem\Sftp\SftpAdapter::class)) {
            throw new RequirementsException(
                ['league/flysystem-sftp'],
                'Sftp'
            );
        }

        $adapter = new Adapter($this->options);

        return $adapter;
    }

    /**
     * {@inheritdoc}
     */
    protected function validateConfig()
    {
        if (! isset($this->options['host'])) {
            throw new UnexpectedValueException("Missing 'host' as option");
        }

        if (! isset($this->options['port'])) {
            throw new UnexpectedValueException("Missing 'port' as option");
        }

        if (! isset($this->options['username'])) {
            throw new UnexpectedValueException("Missing 'username' as option");
        }

        if (! isset($this->options['password']) && ! isset($this->options['privateKey'])) {
            throw new UnexpectedValueException("Missing either 'password' or 'privateKey' as option");
        }
    }
}
