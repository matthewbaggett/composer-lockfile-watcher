<?php

namespace ComposerWatcher;

use Composer\Command\BaseCommand;
use Spatie\Watcher\Watch;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class WatchCommand extends BaseCommand
{
    public const CHECK_INTERVAL_SECONDS = 3;
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

        // This solution requires chokidar and that depends on npm and friends...
        #Watch::path($lockFile)->onAnyChange(function(string $type, string $path) use ($output){
        #    $output->writeln("Running composer install");
        #    if($type === Watch::EVENT_TYPE_FILE_UPDATED){
        #        $this->getApplication()->doRun(new StringInput("install"), $output);
        #    }
        #})->start();

        while(true) {
            $md5 = md5_file($lockFile);
            while ($md5 == md5_file($lockFile)) {
                sleep(self::CHECK_INTERVAL_SECONDS);
            }
            $this->getApplication()->doRun(new StringInput("install"), $output);
        }

        return 0;
    }
}
