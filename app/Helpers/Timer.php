<?php


namespace App\Helpers;


class Timer
{
    private $start;
    private $stop;

    private $last;
    private $current;

    private $running = false;

    public function __construct() {
        $this->start = $this->now();
        $this->current = $this->now();
        $this->last = $this->now();
        $this->running = true;
    }

    public static function now() {
        return microtime(true);
    }

    // Returns the time since the last lap.
    public function lap() {
        $this->current = $this->now();
        $delta = $this->current - $this->last;
        $this->last = $this->current;
        return $delta;
    }

    // Returns the time since the start or reset.
    public function timeElapsed() {
        return $this->running ? $this->now() - $this->start : $this->stop - $this->start;
    }

    // Restarts the timer to 0.
    public function reset() {
        $this->start = $this->now();
        $this->current = $this->now();
        $this->last = $this->now();
    }

    // Stops the timer and returns the elapsed time.
    public function stop() {
        $this->stop = $this->now();
        $this->running = false;
        return $this->timeElapsed();
    }

    public function start() {
        $this->running = true;
        $this->reset();
    }
}
