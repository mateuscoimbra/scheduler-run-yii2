<?php

namespace SuaLib\SchedulerRunYii2\components;

use Yii;
use SuaLib\SchedulerRunYii2\schedule\Schedule;
use yii\base\Component;

class Scheduler extends Component
{
    /** @var string */
    public $tasksClass;

    public function schedule(Schedule $schedule)
    {
        // Esta função deve ser sobrescrita pelo desenvolvedor no arquivo de configuração
        // para definir as tarefas.
    }

    public function getTasks(): Schedule
    {
        $schedule = new Schedule();
        $this->schedule($schedule);
        return $schedule;
    }
}