<?php

namespace ComposerWatcher;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WatchCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->setName('watch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lockFile = getcwd() . "/composer.lock";
        if(!file_exists($lockFile)){
            $output->writeln("Can't find composer.lock");
            return 1;
        }

        $output->writeln(sprintf("Found composer.lock: %s", md5_file($lockFile)));

        return 0;
    }
}
