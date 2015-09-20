<?php

namespace Listener;

use Event\InputEvent;

class GreenButtonListener
{
    public function handle(InputEvent $event)
    {
        if (!$event->getProcess()->isRunning()) {
            echo 'Starting...', PHP_EOL;
            $event->getProcess()->start();
        }
    }
}