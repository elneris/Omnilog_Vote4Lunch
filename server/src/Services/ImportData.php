<?php


namespace App\Services;


use App\Entity\Place;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImportData extends AbstractController
{
    public function importData($data)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($data);
        $entityManager->flush();

        return true;
    }

    public function deleteData()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $placeRepository = $this->getDoctrine()->getRepository(Place::class);
        $places = $placeRepository->findAll();

        if ($places > 0) {
            foreach ($places as $place) {
                $entityManager->remove($place);
            }
        }

        return true;
    }
}