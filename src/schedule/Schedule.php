<?php

namespace SuaLib\SchedulerRunYii2\schedule;

class Schedule
{
    protected $events = [];

    public function command(string $command): Event
    {
        $event = new Event('yii ' . $command);
        $this->events[] = $event;
        return $event;
    }

    public function exec(string $command): Event
    {
        $event = new Event($command);
        $this->events[] = $event;
        return $event;
    }
    
    public function call(callable $callback): Event
    {
        $event = new Event($callback);
        $this->events[] = $event;
        return $event;
    }

    public function getDueEvents(): array
    {
        return array_filter($this->events, fn(Event $event) => $event->isDue());
    }

    public function run()
    {
        $dueEvents = $this->getDueEvents();
        if (empty($dueEvents)) {
            echo "No scheduled tasks are due to run.\n";
            return;
        }

        foreach ($dueEvents as $event) {
            $event->run();
        }
    }
}