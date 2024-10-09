<?php

namespace App\Tests\Model;

use App\Entity\Task;
use App\Entity\Developer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    /**
     * @test
     * @return void
     */
    public function testTaskIsPersistedInDatabase()
    {
        $em = self::$container->get('doctrine')->getManager();

        $developerEntity = new Developer();
        $developerEntity->setName('DEV1');
        $developerEntity->setLevel(2);
        $developerEntity->setHour(1.0);

        $em->persist($developerEntity);
        $em->flush();

        $taskEntity = new Task();
        $taskEntity->setName('trial');
        $taskEntity->setDuration(5.0);
        $taskEntity->setDifficulty(3);
        $taskEntity->setDeveloper($developerEntity);

        $em->persist($taskEntity);
        $em->flush();

        $task = $em->getRepository(Task::class)->find($taskEntity->getId());

        $this->assertEquals('trial', $task->getName());
        $this->assertEquals(5.0, $task->getDuration());
        $this->assertEquals(2, $task->getDifficulty());
        $this->assertEquals($developerEntity->getId(), $task->getDeveloper()->getId());
    }
}