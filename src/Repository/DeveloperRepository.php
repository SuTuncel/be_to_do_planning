<?php

namespace App\Repository;

use App\Entity\Developer;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;

class DeveloperRepository extends ServiceEntityRepository
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Developer::class);
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAll(): array
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
    public function save(Developer $developer): void
    {
        $this->em->persist($developer);
        $this->em->flush();
    }

    /**
     * @return int
     */
    private function findEstimatedHour(Task $task): int
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
