<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Repository\DeveloperRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskOrganizerController extends AbstractController
{
    private $developerRepository;
    private $taskRepository;

    public function __construct(DeveloperRepository $developerRepository, TaskRepository $taskRepository)
    {
        $this->developerRepository = $developerRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/", name="organize_tasks")
     */
    public function indexAction(): Response
    {
        $this->addDevelopers();

        $developers = $this->developerRepository->getAll();
        $tasks = $this->taskRepository->findAll();

        $developerSchedules = [];
        $totalWeeks = 0;

        foreach ($tasks as $task) {
            $taskWorkload = $task->getDifficulty() * $task->getDuration();

            usort($developers, function ($a, $b) {
                return $a->getHour() - $b->getHour();
            });

            $developer = $developers[0];

            $workPerWeek = 45 * $developer->getLevel();
            $weeksRequired = ceil($taskWorkload / $workPerWeek);

            $developer->setHour($developer->getHour() + ($weeksRequired * 45));

            $this->taskRepository->assignDeveloper($task, $developer);

            $developerSchedules[$developer->getName()][] = [
                'task_id' => $task->getId(),
                'task_name' => $task->getName(),
                'weeks_required' => $weeksRequired
            ];

            $totalWeeks = max($totalWeeks, $developer->getHour() / 45);
        }

        return $this->render('task_organizer/organize.html.twig', [
            'developerSchedules' => $developerSchedules,
            'totalWeeks' => $totalWeeks
        ]);
    }

    private function addDevelopers()
    {
        $existingDevelopers = $this->developerRepository->getAll();

        if (count($existingDevelopers) === 0) {
            $developers = [
                ['name' => 'DEV1', 'level' => 1, 'hour' => 1],
                ['name' => 'DEV2', 'level' => 2, 'hour' => 1],
                ['name' => 'DEV3', 'level' => 3, 'hour' => 1],
                ['name' => 'DEV4', 'level' => 4, 'hour' => 1],
                ['name' => 'DEV5', 'level' => 5, 'hour' => 1],
            ];

            foreach ($developers as $developer) {
                $developerEntity = new Developer();
                $developerEntity->setName($developer['name']);
                $developerEntity->setLevel($developer['level']);
                $developerEntity->setHour($developer['hour']);

                $this->developerRepository->save($developerEntity);
            }
        }
    }
}
