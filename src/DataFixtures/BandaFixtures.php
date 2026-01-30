<?php

namespace App\DataFixtures;

use App\Entity\Banda;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BandaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
                $bandas = [
            [
                'Los Cangrejos Eléctricos', 'Rock',
                'Banda de rock alternativo con influencias noventeras.',
                'https://cangrejos.com',
                'cangrejos@music.com', '600123456', 'Valencia',
                'https://picsum.photos/seed/cangrejos/300/200'
            ],
            [
                'Ritmo Lunar', 'Pop',
                'Pop suave con toques electrónicos.',
                'https://ritmolunar.es',
                'lunar@music.com', '611223344', 'Castellón',
                'https://picsum.photos/seed/lunar/300/200'
            ],
            [
                'Furia Marina', 'Metal',
                'Metal melódico inspirado en el mar.',
                'https://furiamarina.com',
                'furia@music.com', '622334455', 'Alicante',
                'https://picsum.photos/seed/marina/300/200'
            ],
            [
                'Neblina Roja', 'Synthwave',
                'Synthwave retro con estética ochentera.',
                'https://neblinaroja.com',
                'neblina@music.com', '677889900', 'Zaragoza',
                'https://picsum.photos/seed/neblina/300/200'
            ],
        ];

        foreach ($bandas as $b) {
            $banda = new Banda();
            $banda->setNombre($b[0]);
            $banda->setGenero($b[1]);
            $banda->setBio($b[2]);
            $banda->setEnlaces($b[3]);
            $banda->setEmail($b[4]);
            $banda->setTelefono($b[5]);
            $banda->setCiudad($b[6]);
            $banda->setImagen($b[7]);
            $banda->setFechaInscripcion(new \DateTime());

            $manager->persist($banda);
        }

        $manager->flush();

    }
}
