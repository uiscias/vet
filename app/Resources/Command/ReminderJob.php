<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Controller\VetController;

class ReminderJob extends Command
{

    protected function configure()
    {
        $this->setName('AppBundle:reminder_job')
            ->setDescription('Send reminder job');
            //->addArgument('my_argument', InputArgument::OPTIONAL, 'Explicamos el significado del argumento');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vet = new VetController();
        $vet->reminderJobAction();
    }
}