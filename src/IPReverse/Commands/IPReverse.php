<?php

namespace Arall\IPReverse\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Arall\IPReverse as Tool;

libxml_use_internal_errors(true);

class IPReverse extends Command
{
    public function configure()
    {
        $this
            ->setName('ip:reverse')
            ->setDescription('List all websites hosted on a IP')
            ->addArgument(
                'ip',
                InputArgument::REQUIRED,
                'IP'
            )
            ->addArgument(
                'service',
                InputArgument::OPTIONAL,
                'Service [bing|hurricane]'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $ipReverse = new Tool($input->getArgument('ip'));
            // Run

            $hosts = $ipReverse->execute($input->getArgument('service') ?: 'bing');
        } catch (Exception $e) {

            return $output->writeln('<error>'.$e->getMessage().'</error>');
        }

        if (!empty($hosts)) {
            foreach ($hosts as $host) {
                $output->writeln($host);
            }
        } else {
            $output->writeln('<error>No hosts found</error>');
        }
    }
}
