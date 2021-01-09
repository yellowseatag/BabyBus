<?php


namespace BabyBus\Account\Util;


use Hyperf\Contract\ContainerInterface;


class Container implements ContainerInterface
{

    protected static $instance;

    /**
     * Get the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * @inheritDoc
     */
    public function make(string $name, array $parameters = [])
    {
        // TODO: Implement make() method.
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, $entry)
    {
        // TODO: Implement set() method.
    }

    /**
     * @inheritDoc
     */
    public function define(string $name, $definition)
    {
        // TODO: Implement define() method.
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        // TODO: Implement has() method.
    }
}