<?php

namespace Tygh;

use InvalidArgumentException;
use Pimple\Container;
use Psr\Container\ContainerInterface;
use Tygh\Core\ApplicationInterface;
use Tygh\Core\BootstrapInterface;

/**
 * Application class provides methods for handling current request and stores common runtime state.
 * It is also an IoC container.
 *
 * @package Tygh
 */
class Application extends Container implements ApplicationInterface
{
    /**
     * @var string Application root directory path
     */
    protected $root_path;

    /**
     * Application constructor.
     *
     * @param string $root_path
     */
    public function __construct($root_path)
    {
        parent::__construct();

        $this->registerCoreServices();
    }

    /**
     * @param string $root_path Application root directory path
     */
    protected function setRootPath($root_path)
    {
        $this->root_path = rtrim($root_path, '\\/');
    }

    /**
     * Registers core services at IoC container.
     *
     * @return void
     */
    protected function registerCoreServices()
    {
        $this['app'] = $this;
    }

    /**
     * @inheritdoc
     */
    public function getRootPath()
    {
        return $this->root_path;
    }

    /**
     * @inheritdoc
     */
    public function bootstrap(array $bootstrapper_list = [])
    {
        foreach ($bootstrapper_list as $bootstrapper) {
            if (is_string($bootstrapper) && is_a($bootstrapper, BootstrapInterface::class, true)) {
                $bootstrapper = new $bootstrapper;
            }

            if ($bootstrapper instanceof BootstrapInterface) {
                /** @var BootstrapInterface $bootstrapper */
                $bootstrapper->boot($this);
            } else {
                throw new InvalidArgumentException(sprintf(
                    'An application bootstrapper must implement the %s interface.',
                    BootstrapInterface::class
                ));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        return $this[$id];
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        return isset($this[$id]);
    }
}
