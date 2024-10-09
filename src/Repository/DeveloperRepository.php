<?php

namespace App\Repository;

use App\Entity\Developer;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\ORMException;

class DeveloperRepository extends EntityRepository
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class)
    {
        $this->em = $em;
        parent::__construct($em, $class);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * @return Developer|object|null
     */
    public function getOne()
    {
        return $this->findOneBy([], ['hour' => 'ASC']);
    }

    /**
     * @param Developer $developer
     * @throws ORMException
     */
    public function save(Developer $developer)
    {
        $this->_em->persist($developer);
        $this->_em->flush();
    }

    /**
     * @return float
     */
    private function findEstimatedHour(Task $task)
    {
        return $task->getDuration() * $task->getDifficulty();

    }

    /**
     * @param Task $task
     * @return Developer|object|null
     * @throws ORMException
     */
    public function assignTask(Task $task)
    {
        $developer = $this->getOne();

        $developer->setHour(
            $developer->getHour() + ($this->findEstimatedHour($task) / $developer->getLevel())
        );

        $this->save($developer);

        return $developer;
    }
}