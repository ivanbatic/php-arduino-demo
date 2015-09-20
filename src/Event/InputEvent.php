<?php

namespace Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Process\Process;

class InputEvent extends Event
{

    private $input;
    /** @var Process  */
    private $process;

    /**
     * InputEvent constructor.
     * @param $input
     */
    public function __construct($input, Process $process = null)
    {
        $this->process = $process;
        $this->input = $input;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }


}