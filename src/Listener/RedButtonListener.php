<?php


namespace Listener;


use Event\InputEvent;

class RedButtonListener
{
    public function handle(InputEvent $event)
    {
        echo 'Stopping...', PHP_EOL;
        $event->getProcess()->signal(SIGKILL);
    }
}