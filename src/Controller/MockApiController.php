<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MockApiController extends AbstractController
{
    /**
     * @Route("/api/mock-one", name="mock_one_api")
     */
    public function getMockOneData(): JsonResponse
    {
        $filePath = __DIR__ . '/../../public/mock/mock-one.txt';

        if (!file_exists($filePath)) {
            return new JsonResponse(['error' => 'File not found'], 404);
        }

        $fileContent = file_get_contents($filePath);
        $data = json_decode($fileContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON format'], 500);
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/mock-two", name="mock_two_api")
     */
    public function getMockTwoData(): JsonResponse
    {
        $filePath = __DIR__ . '/../../public/mock/mock-two.txt';

        if (!file_exists($filePath)) {
            return new JsonResponse(['error' => 'File not found'], 404);
        }

        $fileContent = file_get_contents($filePath);
        $data = json_decode($fileContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON format'], 500);
        }

        return new JsonResponse($data);
    }
}
