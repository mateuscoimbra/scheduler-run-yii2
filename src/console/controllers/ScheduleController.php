<?php

namespace SuaLib\SchedulerRunYii2\console\controllers;

use Yii;
use yii\console\Controller;
use SuaLib\SchedulerRunYii2\components\Scheduler;

class ScheduleController extends Controller
{
    /** @var Scheduler */
    public $scheduler;

    public function init()
    {
        parent::init();
        // Acessa o componente do Yii2. Se não estiver configurado, criará um padrão.
        $this->scheduler = Yii::$app->has('scheduler') ? Yii::$app->scheduler : new Scheduler();
    }

    public function actionRun()
    {
        $schedule = $this->scheduler->getTasks();
        $schedule->run();
    }
}