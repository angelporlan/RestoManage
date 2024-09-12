<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_create', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles($data['roles']);
        $user->setName($data['name']);
        $user->setUsername($data['username']);
        $user->setAddress($data['address'] ?? null);
        $user->setPhone($data['phone'] ?? null);
        $user->setTotalSpent($data['totalSpent']);
        $user->setCounterMonneyOffer($data['counterMonneyOffer']);
        $user->setTotalOrders($data['totalOrders']);
        $user->setOrdersForDiscount($data['ordersForDiscount']);
        $user->setDiscountCode($data['discountCode']);
        $user->setOrders($data['orders']);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['status' => 'User created!'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function listUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                'address' => $user->getAddress(),
                'phone' => $user->getPhone(),
                'totalSpent' => $user->getTotalSpent(),
                'counterMonneyOffer' => $user->getCounterMonneyOffer(),
                'totalOrders' => $user->getTotalOrders(),
                'ordersForDiscount' => $user->getOrdersForDiscount(),
                'discountCode' => $user->getDiscountCode(),
                'orders' => $user->getOrders(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/users/{id}', name: 'user_show', methods: ['GET'])]
    public function showUser(User $user): JsonResponse
    {
        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
            'address' => $user->getAddress(),
            'phone' => $user->getPhone(),
            'totalSpent' => $user->getTotalSpent(),
            'counterMonneyOffer' => $user->getCounterMonneyOffer(),
            'totalOrders' => $user->getTotalOrders(),
            'ordersForDiscount' => $user->getOrdersForDiscount(),
            'discountCode' => $user->getDiscountCode(),
            'orders' => $user->getOrders(),
        ];

        return new JsonResponse($data);
    }

    #[Route('/users/{id}', name: 'user_update', methods: ['PUT'])]
    public function updateUser(Request $request, EntityManagerInterface $entityManager, User $user, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user->setEmail($data['email']);
        if (isset($data['password'])) {
            $user->setPassword($data['password']);
        }
        $user->setRoles($data['roles']);
        $user->setName($data['name']);
        $user->setUsername($data['username']);
        $user->setAddress($data['address'] ?? null);
        $user->setPhone($data['phone'] ?? null);
        $user->setTotalSpent($data['totalSpent']);
        $user->setCounterMonneyOffer($data['counterMonneyOffer']);
        $user->setTotalOrders($data['totalOrders']);
        $user->setOrdersForDiscount($data['ordersForDiscount']);
        $user->setDiscountCode($data['discountCode']);
        $user->setOrders($data['orders']);

        $entityManager->flush();

        return new JsonResponse(['status' => 'User updated!'], JsonResponse::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function deleteUser(EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(['status' => 'User deleted!'], JsonResponse::HTTP_NO_CONTENT);
    }
}
