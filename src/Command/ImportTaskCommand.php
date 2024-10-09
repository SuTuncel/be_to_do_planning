<?php

namespace App\Command;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\TaskProviderFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportTaskCommand extends Command
{
    protected static $defaultName = 'app:import-mocks-task';

    /**
     * @var TaskProviderFactory
     */
    private $taskProviderFactory;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(TaskProviderFactory $taskProviderFactory, TaskRepository $taskRepository)
    {
        parent::__construct();
        $this->taskProviderFactory = $taskProviderFactory;
        $this->taskRepository = $taskRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('It takes the task information in the mock files and saves it to the task table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $providers = ['MockOneProvider', 'MockTwoProvider'];

        foreach ($providers as $providerName) {
            $provider = $this->taskProviderFactory->getProvider($providerName);

            if ($provider) {
                $tasks = $provider->getTasks();

                foreach ($tasks as $task) {
                    $taskEntity = new Task();

                    if ($providerName === 'Mock1Provider') {
                        $taskEntity->setName('Task ' . $task['id']);
                        $taskEntity->setDifficulty($task['value']);
                        $taskEntity->setDuration($task['estimated_duration']);
                    }

                    if ($providerName === 'Mock2Provider') {
                        $taskEntity->setName('Task ' . $task['id']);
                        $taskEntity->setDuration($task['sure']);
                        $taskEntity->setDifficulty($task['zorluk']);
                    }

                    $this->taskRepository->save($taskEntity);
                }
            }
        }


        $io->success('Tasks have been imported');

        return 0;
    }
}
