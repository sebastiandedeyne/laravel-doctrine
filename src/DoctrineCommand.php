<?php

namespace Sebdd\LaravelDoctrine;

use Doctrine\ORM\Version;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Illuminate\Console\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

class DoctrineCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'doctrine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a doctrine command';

    /**
     * Constructor method.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->ignoreValidationErrors();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(EntityManager $entityManager)
    {
        $application = new Application('Doctrine Command Line Interface', Version::VERSION);
        $helperSet = ConsoleRunner::createHelperSet($entityManager);
        $application->setHelperSet($helperSet);
        ConsoleRunner::addCommands($application);
        
        $argv = $_SERVER['argv'];
        array_shift($argv);

        $application->run(
            new ArgvInput($argv),
            $this->output
        );
    }
}
