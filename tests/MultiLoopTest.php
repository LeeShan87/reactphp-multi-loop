<?php

namespace LeeShan87\Tests\React\MultiLoop;

use LeeShan87\React\MultiLoop\MultiLoop;
use React\EventLoop\Factory;

class MultiLoopTest extends \PHPUnit\Framework\TestCase
{

    public function testTick()
    {
        MultiLoop::flushLoops();
        $this->assertEmpty(MultiLoop::getLoops());
        $loop1 = Factory::create();
        $loop1->futureTick($this->expectCallableOnce());
        $this->assertEmpty(MultiLoop::getLoops());
        MultiLoop::loopTick($loop1);
        $loop1->futureTick($this->expectCallableNever());
        $this->assertEmpty(MultiLoop::getLoops());
    }

    public function testLoopManagement()
    {
        MultiLoop::flushLoops();
        $this->assertEmpty(MultiLoop::getLoops());
        $loop1 = Factory::create();
        $loop1->futureTick($this->expectCallableOnce());
        MultiLoop::addLoop($loop1, 'loop1');
        $this->assertCount(1, MultiLoop::getLoops());
        $loop2 = Factory::create();
        $loop2->futureTick($this->expectCallableNever());
        MultiLoop::tickAll();
        $loop3 = Factory::create();
        MultiLoop::addLoop($loop3, 'loop3');
        $this->assertCount(2, MultiLoop::getLoops());
        $loop1->futureTick($this->expectCallableOnce());
        $loop3->futureTick($this->expectCallableOnce());
        MultiLoop::tickAll();
        MultiLoop::removeLoop('loop1');
        $this->assertCount(1, MultiLoop::getLoops());
        $loop1->futureTick($this->expectCallableNever());
        $loop3->futureTick($this->expectCallableOnce());
        MultiLoop::tickAll();
        MultiLoop::flushLoops();
        $this->assertEmpty(MultiLoop::getLoops());
    }

    public function testWaitForSeconds()
    {
        MultiLoop::flushLoops();
        $this->assertEmpty(MultiLoop::getLoops());
        $loop1 = Factory::create();
        $loop1->addTimer(1, $this->expectCallableOnce());
        $loop2 = Factory::create();
        $loop2->futureTick($this->expectCallableOnce());
        $loop1->addTimer(3, $this->expectCallableNever());
        MultiLoop::addLoop($loop1, 'loop1');
        MultiLoop::addLoop($loop2, 'loop2');
        $this->assertCount(2, MultiLoop::getLoops());
        MultiLoop::waitForSeconds(2);
    }

    protected function expectCallableExactly($amount)
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->exactly($amount))
            ->method('__invoke');

        return $mock;
    }

    protected function expectCallableOnce()
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke');

        return $mock;
    }

    protected function expectCallableOnceWith($value)
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($value);

        return $mock;
    }

    protected function expectCallableNever()
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->never())
            ->method('__invoke');

        return $mock;
    }

    protected function expectCallableConsecutive($numberOfCalls, array $with)
    {
        $mock = $this->createCallableMock();

        for ($i = 0; $i < $numberOfCalls; $i++) {
            $mock
                ->expects($this->at($i))
                ->method('__invoke')
                ->with($this->equalTo($with[$i]));
        }

        return $mock;
    }

    protected function createCallableMock()
    {
        return $this
            ->getMockBuilder(CallableStub::class)
            ->getMock();
    }
}
class CallableStub
{
    public function __invoke()
    {
    }
}
