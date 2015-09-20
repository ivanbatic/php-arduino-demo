<?php

namespace Connection;

class BoardConnection
{

    /** @var string */
    private $source;

    /** @var int */
    private $baud = 9600;

    /** @var resource */
    private $deviceHandle;

    /**
     * BoardConnection constructor.
     * @param $source
     * @param $baud
     */
    public function __construct($source, $baud)
    {
        $this->source = $source;
        $this->baud = $baud;

        /** @noinspection PhpUndefinedConstantInspection */
        $this->deviceHandle = dio_open($source, O_RDWR | O_NONBLOCK | O_NOCTTY);

        if ($this->deviceHandle === false) {
            throw new \ErrorException("Sorry, could not connect to $source");
        }

        dio_tcsetattr($this->deviceHandle, ['baud' => $baud]);
    }

    /**
     * @param string $message
     */
    public function send($message)
    {
        dio_write($this->deviceHandle, $message, strlen($message));
    }

    /**
     * @param int $chunkSize
     * @return string
     */
    public function receive($chunkSize = 1024)
    {
        $chunk = dio_read($this->deviceHandle, $chunkSize);

        return $chunk;
    }
}