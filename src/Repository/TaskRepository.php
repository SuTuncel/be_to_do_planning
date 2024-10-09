<?php

namespace App\Repository;

use App\Domain\Exception\InvalidArgumentException;
use App\Entity\Developer;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;

/**
 * Class TaskRepository
 * @package App\Repository
 */
class TaskRepository extends ServiceEntityRepository
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Task::class);
        $this->em = $em;
    }

    /**
     * @param Task $task
     * @throws ORMException
     */
    public function save(Task $task)
    {
        $this->em->persist($task);
        $this->em->flush();
    }

    /**
     * @param Task $task
     * @param Developer $developer
     * @throws ORMException
     * @throws InvalidArgumentException
     */
    public function assignDeveloper(Task $task, Developer $developer)
    {
        $task->setDeveloper($developer);
        $this->save($task);
    }
}
