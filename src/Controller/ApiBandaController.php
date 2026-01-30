<?php

namespace App\Controller;

use App\Entity\Banda;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Attribute\Route;

class ApiBandaController
{
    #[Route('/api/inscripcion', name: 'api_inscripcion_banda', methods: ['POST'])]
    public function inscribir(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $banda = new Banda();
        $banda->setNombre($data['nombre'] ?? '');
        $banda->setGenero($data['genero'] ?? '');
        $banda->setBio($data['bio'] ?? null);
        $banda->setEnlaces($data['enlaces'] ?? null);
        $banda->setEmail($data['email'] ?? '');
        $banda->setTelefono($data['telefono'] ?? null);
        $banda->setCiudad($data['ciudad'] ?? null);
        $banda->setImagen($data['imagen'] ?? null);
        $banda->setFechaInscripcion(new \DateTime());

        $em->persist($banda);
        $em->flush();

        return new JsonResponse(['message' => 'Banda inscrita correctamente'], 201);
    }


    #[Route('/api/bandas', name: 'api_listar_bandas', methods: ['GET'])]
    public function listar(EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(Banda::class);
        $bandas = $repo->findAll();

        $data = [];
        foreach ($bandas as $banda) {
            $data[] = [
                'id' => $banda->getId(),
                'nombre' => $banda->getNombre(),
                'genero' => $banda->getGenero(),
                'bio' => $banda->getBio(),
                'enlaces' => $banda->getEnlaces(),
                'email' => $banda->getEmail(),
                'telefono' => $banda->getTelefono(),
                'ciudad' => $banda->getCiudad(),
                'imagen' => $banda->getImagen(),
                'fechaInscripcion' => $banda->getFechaInscripcion()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }
}

