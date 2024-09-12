<?php

namespace App\Controller;

use App\Entity\Reward;
use App\Repository\RewardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RewardController extends AbstractController
{
    #[Route('/reward', name: 'create_reward', methods: ['POST'])]
    public function createReward(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $value = $data['value'] ?? null;

        if ($value === null) {
            return new JsonResponse(['error' => 'Value is required'], 400);
        }

        $reward = new Reward();
        $reward->setCode($this->generateRandomCode());
        $reward->setValue($value);

        $entityManager->persist($reward);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $reward->getId(),
            'code' => $reward->getCode(),
            'value' => $reward->getValue()
        ], 201);
    }

    #[Route('/reward/{code}', name: 'delete_reward', methods: ['DELETE'])]
    public function deleteReward(string $code, RewardRepository $rewardRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $reward = $rewardRepository->findOneBy(['code' => $code]);

        if (!$reward) {
            return new JsonResponse(['error' => 'Reward not found'], 200);
        }

        $entityManager->remove($reward);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Reward deleted', 'value' => $reward->getValue()], 200);
    }

    private function generateRandomCode(int $length = 12): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
