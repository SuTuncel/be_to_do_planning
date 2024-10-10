<?php

namespace App\Tests\Controller;

use App\Entity\Developer;
use App\Entity\Task;
use App\Repository\DeveloperRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskOrganizerControllerTest extends WebTestCase
{
    private $client;
    private $developerRepository;
    private $taskRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->developerRepository = $this->createMock(DeveloperRepository::class);
        $this->taskRepository = $this->createMock(TaskRepository::class);
        static::getContainer()->set('App\Repository\DeveloperRepository', $this->developerRepository);
        static::getContainer()->set('App\Repository\TaskRepository', $this->taskRepository);
    }

    public function testIndexActionRendersCorrectly(): void
    {
        $developerOne = new Developer();
        $developerOne->setName('DEV1');
        $developerOne->setLevel(1);
        $developerOne->setHour(1);

        $developerTwo = new Developer();
        $developerTwo->setName('DEV2');
        $developerTwo->setLevel(2);
        $developerTwo->setHour(2);

        $developers = [$developerOne, $developerTwo];

        $taskOne = new Task();
        $taskOne->setName('Task 1');
        $taskOne->setDifficulty(2);
        $taskOne->setDuration(3);

        $taskTwo = new Task();
        $taskTwo->setName('Task 2');
        $taskTwo->setDifficulty(3);
        $taskTwo ->setDuration(1);

        $tasks = [$taskOne, $taskTwo];

        $this->developerRepository
            ->method('getAll')
            ->willReturn($developers);

        $this->taskRepository
            ->method('findAll')
            ->willReturn($tasks);

        $this->taskRepository
            ->expects($this->exactly(2))
            ->method('assignDeveloper');

        $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('Task Scheduling', $response->getContent());
        $this->assertStringContainsString('DEV1', $response->getContent());
        $this->assertStringContainsString('Task 1', $response->getContent());
        $this->assertStringContainsString('Task 2', $response->getContent());
    }
}