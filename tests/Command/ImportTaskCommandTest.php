<?php

namespace App\Tests\Command;

use App\Repository\TaskRepository;
use App\Service\TaskProviderFactory;
use App\Command\ImportTaskCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ImportTaskCommandTest extends TestCase
{
    private $taskProviderFactory;
    private $taskRepository;
    private $commandTester;

    protected function setUp(): void
    {
        $this->taskProviderFactory = $this->createMock(TaskProviderFactory::class);
        $this->taskRepository = $this->createMock(TaskRepository::class);

        $command = new ImportTaskCommand($this->taskProviderFactory, $this->taskRepository);

        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($application->find('app:import-mocks-task'));
        $this->commandTester = $commandTester;
    }

    public function testExecuteSuccessfullyImportsTasks()
    {
        $mockOneDatas = [
            ['id' => 1, 'value' => 4, 'estimated_duration' => 3],
            ['id' => 2, 'value' => 2, 'estimated_duration' => 7],
        ];

        $mockTwoDatas = [
            ['id' => 2, 'zorluk' => 2, 'sure' => 4],
            ['id' => 4, 'zorluk' => 3, 'sure' => 5],
        ];

        $mockOneProvider = $this->createMock(TaskProviderInterface::class);
        $mockOneProvider->method('getTasks')->willReturn($mockOneDatas);

        $mockTwoProvider = $this->createMock(TaskProviderInterface::class);
        $mockTwoProvider->method('getTasks')->willReturn($mockTwoDatas);

        $this->taskProviderFactory->method('getProvider')
            ->willReturnMap([
                ['App\Service\MockOneProvider', $mockOneProvider],
                ['App\Service\MockTwoProvider', $mockTwoProvider],
            ]);

        $this->taskRepository->expects($this->exactly(4))->method('save');

        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Tasks have been imported', $output);

        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }
}