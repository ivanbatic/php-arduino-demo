<?php

namespace Command;

use Connection\BoardConnection;
use Event\InputEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Process\Process;

class ArduinoListenerCommand extends Command
{

    /** @var BoardConnection */
    protected $device;

    /** @var Process */
    protected $process = null;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('listen')->setDescription('Listens to the input from the Arduino')
            ->addOption('device', 'd', InputOption::VALUE_OPTIONAL, 'Set the device to use (unix device or COM port)', '/dev/tty.usbmodem1411')
            ->addOption('baud', 'b', InputOption::VALUE_OPTIONAL, 'Set the BAUD', 9600)
            ->addArgument('process', InputArgument::OPTIONAL, 'Set the process to be controlled by the device')
            ->addOption('interval', 'i', InputOption::VALUE_OPTIONAL, 'Frequency interval', 100000);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting the hoax bomb...');
        $this->device = new BoardConnection($input->getOption('device'), $input->getOption('baud'));

        if($processName = $input->getArgument('process')){
            $this->process = new Process($processName);
            $output->writeln("Process set: $processName");
        } else {
            $output->writeln('No process set.');
        }

        $output->writeln("Listening...");

        $this->listen($input->getOption('interval'));


    }

    private function listen($interval)
    {
        do {
            $input = $this->device->receive();
            if (!empty($input)) {
                $event = new InputEvent($input, $this->process);

                $this->eventDispatcher->dispatch('input', $event);
                $this->eventDispatcher->dispatch($input, $event);
            }
            usleep($interval);
        } while (true);
    }
}