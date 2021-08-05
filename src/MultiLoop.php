<?php

namespace LeeShan87\React\MultiLoop;

use React\EventLoop\LoopInterface;

class MultiLoop
{
    /**
     * @var LoopInterface[]
     */
    protected static $loops = [];

    /**
     * Add a LoopInterface to the managed event loops
     *
     * @param LoopInterface $loop
     * @param string $name
     * @return void
     */
    public static function addLoop($loop,  $name)
    {
        self::$loops[$name] = $loop;
    }

    /**
     * Remove the given loop from the managed loops
     *
     * @param string $name
     * @return void
     */
    public static function removeLoop($name)
    {
        unset(self::$loops[$name]);
    }
    /**
     * @return void
     */
    public static function flushLoops()
    {
        foreach (array_keys(self::$loops) as $key) {
            unset(self::$loops[$key]);
        }
    }
    /**
     * Returns all added LoopInterfaces
     *
     * @return LoopInterface[]
     */
    public static function getLoops()
    {
        return self::$loops;
    }

    /**
     * Runs only one tick on a LoopInterface.
     *
     * @var LoopInterface $loop
     * @return void
     */
    public static function loopTick($loop)
    {
        $loop->futureTick(function () use ($loop) {
            $loop->stop();
        });
        $loop->run();
    }
    /**
     * Run loopTick on all added LoopInterface
     *
     * @return void
     */
    public static function tickAll()
    {
        foreach (self::$loops as $loop) {
            self::loopTick($loop);
        }
    }

    /**
     * @param int $seconds
     * @return void
     */
    public static function waitForSeconds($seconds)
    {
        $startTime = time();
        while (time() < ($startTime + $seconds)) {
            self::tickAll();
        }
    }
}
