<?php

namespace Pkboom\TestCreator\Test;

use PHPUnit\Framework\TestCase;
use Pkboom\TestWatcher\TestWatcherCommand;

class TestWatcherCommandTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $command = new TestWatcherCommand();

        $this->assertInstanceOf(TestWatcherCommand::class, $command);
    }
}
