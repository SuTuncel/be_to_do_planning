<?php

namespace App\Repository;

use App\Entity\Developer;
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
     * @param Developer $developer
     * @throws ORMException
     */
    public function save(Developer $developer): void
    {
        $this->em->persist($developer);
        $this->em->flush();
    }
}
