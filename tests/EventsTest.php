<?php

use \PHPUnit\Framework\TestCase;
use listen\Events;

class EventsTest extends TestCase {
    public function testEvents() {
        $this->expectOutputString("inside a test event");

        Events::listen("testevent", function($test) {
            echo $test;
        });

        Events::trigger("testevent", ["inside a test event"]);
    }
}