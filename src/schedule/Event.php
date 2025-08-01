<?php

namespace SuaLib\SchedulerRunYii2\schedule;

use Dragonmantank\Cron\CronExpression;

class Event
{
    /** @var string|callable */
    public $command;

    /** @var string */
    protected $expression = '* * * * *';

    /** @var string|null */
    public $description;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function setExpression(string $expression): self
    {
        $this->expression = $expression;
        return $this;
    }

    public function isDue(): bool
    {
        $cron = new CronExpression($this->expression);
        return $cron->isDue();
    }

    public function run()
    {
        echo "Running task: " . ($this->description ?? $this->getCommandDescription()) . "\n";
        
        if (is_callable($this->command)) {
            call_user_func($this->command);
        } else {
            exec($this->command);
        }
    }

    protected function getCommandDescription(): string
    {
        if (is_string($this->command)) {
            return $this->command;
        }
        return 'Callable Function';
    }

    // Métodos para agendamento (fluent interface)
    public function everyMinute(): self { $this->expression = '* * * * *'; return $this; }
    public function hourly(): self { $this->expression = '0 * * * *'; return $this; }
    public function daily(): self { $this->expression = '0 0 * * *'; return $this; }
    // ... adicione mais métodos conforme sua necessidade (weekly, monthly, etc.)

    public function cron(string $expression): self
    {
        $this->expression = $expression;
        return $this;
    }

    public function then(callable $callback): self
    {
        // Aqui você pode adicionar um hook para ser executado após a tarefa
        return $this;
    }
}