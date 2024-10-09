<?php

namespace App\Repository;

use App\Domain\Exception\InvalidArgumentException;
use App\Entity\Developer;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\ORMException;

/**
 * Class TaskRepository
 * @package App\Infrastructure\Repository
 */
class TaskRepository extends EntityRepository
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class)
    {
        $this->em = $em;
        parent::__construct($em, $class);
    }

    /**
     * @param Task $task
     * @throws ORMException
     */
    public function save(Task $task)
    {
        $this->_em->persist($task);
        $this->_em->flush();
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