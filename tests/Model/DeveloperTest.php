<?php

namespace App\Tests\Model;

use App\Entity\Developer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeveloperTest extends KernelTestCase
{
    /**
     * @test
     * @return void
     */
    public function testDeveloperIsPersistedInDatabase()
    {
        $em = self::$container->get('doctrine')->getManager();

        $entity = new Developer();
        $entity->setName('DEV1');
        $entity->setLevel(1);
        $entity->setHour(2);

        $em->persist($entity);
        $em->flush();

        $developerRepository = $em->getRepository(Developer::class);
        $developer = $developerRepository->find($entity->getId());

        $this->assertEquals('DEV1', $developer->getName());
        $this->assertEquals(1, $developer->getLevel());
        $this->assertEquals(3, $developer->getHour());
    }
}