<?php

namespace listen;

abstract class Events {
	static private $listeners = [];
	
	static public function listen(string $event, callable $listener) {
		if(!self::exists($event)) self::$listeners[$event] = [];
		self::$listeners[$event][] = $listener;
	}
    
    /**
     * Triggers all of an event's listeners.
     */
	static public function trigger(string $event, array $args = [], bool $delete = true, $newthis = null): int {
		if(!self::exists($event)) return 0;
        

        foreach(self::$listeners[$event] as $listener) {
            static $count = 0;

            if($newthis) $listener = $listener->bindTo($newthis);
            $listener(...$args);

            $count++;
        }

        if($delete) self::delete($event);
		return $count;
	}
    
    static public function attach(string $event, array $defaults, callable $function, int $maxcalls = 1) {
        if(!self::exists($event)) return $function(...$defaults);

        for($i = 0; ($i < $maxcalls && $i < count(self::$listeners[$event])); $i++) {
            $function(...self::$listeners[$event][$i]());
        }
    }

	static public function count(string $event) {
		return (self::exists($event)) ? count(self::$listeners[$event]) : 0;
    }
    
    static public function exists(string $event): bool {
        return isset(self::$listeners[$event]);
    }

    static public function delete(string $event): bool {
        if(!self::exists($event)) return false;
        unset(self::$listeners[$event]);
        return true;
    }
}