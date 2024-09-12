<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dish;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Exception;

class DishController extends AbstractController
{
    /**
     * @Route("/dishes", name="create_dish", methods={"POST"})
     */
    public function createDish(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $dish = new Dish();
        $dish->setNombre($data['nombre']);
        $dish->setPeso($data['peso']);
        $dish->setCategoria($data['categoria']);
        $dish->setPrecio($data['precio']);
        $dish->setDescripcion($data['descripcion']);
        $dish->setIngredientes($data['ingredientes']);
        $dish->setInfoNutri($data['infoNutri']);
        $dish->setUso($data['uso']);

        $fotoData = base64_decode($data['foto'], true);
        if ($fotoData === false) {
            throw new Exception('Invalid base64 string');
        }
        
        $fotoName = 'dish' . uniqid() . '.jpg';
        $fotoPath = '../../restomanage/src/assets/dishes/' . $fotoName; 
        
        if (file_put_contents($fotoPath, $fotoData) === false) {
            // Handle error
            throw new Exception('Failed to write image file');
        }
        
        $dish->setFoto($fotoName); 

        $entityManager->persist($dish);
        $entityManager->flush();

        return new Response('Dish created successfully', Response::HTTP_CREATED);
    }
}
