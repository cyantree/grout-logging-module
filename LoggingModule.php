<?php
namespace Grout\Cyantree\LoggingModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\Event\Event;
use Cyantree\Grout\Logging;
use Grout\Cyantree\LoggingModule\Types\LoggingConfig;

class LoggingModule extends Module
{
    /** @var Logging */
    public $l;

    public $logDefaultChannel = true;

    /** @var LoggingConfig */
    public $moduleConfig;

    public function init()
    {
        $this->app->configs->setDefaultConfig($this->id, new LoggingConfig());

        $this->moduleConfig = $this->app->configs->getConfig($this->id);
        $this->moduleConfig->file = $this->app->parseUri($this->moduleConfig->file);

        $this->l = new Logging();
        $this->l->file = $this->app->parseUri($this->moduleConfig->file);
        $this->l->start('START ' . $this->app->getConfig()->projectTitle, $this->app->timeConstructed);

        $this->app->events->join('log', array($this, 'onLog'));
        $this->app->events->join('log0', array($this, 'onLog'));

        $this->addRoute('', 'Pages\LoggingPage');
    }

    public function logChannel($id)
    {
        $this->app->events->join('log' . $id, array($this, 'onLog'));
    }

    /** @param \Cyantree\Grout\Event\Event $event */
    public function onLog($event)
    {
        if ($event->type == 'log' && !$this->logDefaultChannel) {
            return;
        }

        $this->l->log($event->data);
    }

    public function destroy()
    {
        $this->l->stop('END ' . $this->app->getConfig()->projectTitle);
    }
}
