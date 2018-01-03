<?php

use \PHPUnit\Framework\TestCase;
use listen\Events;

class EventsTest extends TestCase {
    public function testEventTrigger() {
        $this->expectOutputString("inside a test event");

        Events::listen("testevent", function($test) {
            echo $test;
        });

        Events::trigger("testevent", ["inside a test event"]);
    }

    public function testEventCount() {
        $this->assertEquals(0, Events::count("testevent"));

        Events::listen("testevent", function() {});
        $this->assertEquals(1, Events::count("testevent"));

        Events::trigger("testevent", [], false);
        $this->assertEquals(1, Events::count("testevent"));
        
        Events::trigger("testevent", []);
        $this->assertEquals(0, Events::count("testevent"));

        Events::listen("testevent", function() {});
        Events::listen("testevent", function() {});
        $this->assertEquals(2, Events::count("testevent"));

        Events::trigger("testevent", []); // cleanup
    }

    public function testEventDelete() {
        $this->assertEquals(0, Events::count("testevent"));

        Events::listen("testevent", function() {});
        Events::listen("testevent", function() {});
        $this->assertEquals(2, Events::count("testevent"));

        $this->assertTrue(Events::delete("testevent"));
        $this->assertEquals(0, Events::count("testevent"));
    }

    public function testEventExists() {
        $this->assertFalse(Events::exists("testevent"));
        
        Events::listen("testevent", function() {});
        $this->assertTrue(Events::exists("testevent"));
        
        Events::delete("testevent");
        $this->assertFalse(Events::exists("testevent"));
    }

    public function testAttachWithListener() {
        $this->expectOutputString("hi");

        Events::listen("testevent", function() {
            return ["hi"];
        });

        Events::attach("testevent", ["no"], function($out) {
            echo $out;
        });

        Events::delete("testevent");
    }

    public function testAttachDefaults() {
        $this->expectOutputString("no");

        Events::attach("testevent", ["no"], function($out) {
            echo $out;
        });

        Events::delete("testevent");
    }
}