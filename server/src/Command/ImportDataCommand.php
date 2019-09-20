<?php

namespace App\Command;

use App\Entity\Place;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDataCommand extends Command
{
    private $entityManager;

    protected static $defaultName = 'app:import-data';

    public function __construct(string $name = null, EntityManager $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import places from json data')
            ->setHelp('This command will import the data from the place.json file to your database. Make sure you have create your database before and the good schema');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonPlaces = file_get_contents('./json_data/places.json');
        $places = json_decode($jsonPlaces);

        foreach ($places as $place) {

            $restaurant = new Place();

            $housenumber = null;
            $street = null;
            $address = null;
            $city = null;
            $phone = null;
            $email = null;
            $website = null;
            $openingHours = null;
            $cuisine = null;
            $regPhone = '/^(?:(?:\+ | 00)33 | 0)\s * ([1 - 9])(?:[\s . -] * (\d{2}))(?:[\s . -] * (\d{2}))(?:[\s . -] * (\d{2}))(?:[\s . -] * (\d{2}))$/';

            $addrStreet = null;
            $addrNumber = null;
            $contactStreet = null;
            $contactNumber = null;

            if (isset($place->tags->{'addr:housenumber'})){
                $addrNumber = $place->tags->{'addr:housenumber'};
            }

            if (isset($place->tags->{'addr:street'})){
                $addrStreet = $place->tags->{'addr:street'};
            }

            if (isset($place->tags->{'contact:housenumber'})){
                $contactNumber = $place->tags->{'contact:housenumber'};
            }

            if (isset($place->tags->{'contact:street'})){
                $contactStreet = $place->tags->{'contact:street'};
            }

            if ($addrNumber && $addrStreet) {
                $address = $addrNumber . ' ' . $addrStreet;
            } elseif ($contactNumber && $contactStreet) {
                $address = $contactNumber . ' ' . $contactStreet;
            } elseif ($addrStreet) {
                $address = $addrStreet;
            } elseif ($contactStreet) {
                $address = $contactStreet;
            }

            dd($input);


            if ((isset($place->tags->{'addr:city'}) || isset($place->tags->{'contact:city'}))) {
                $city = $place->tags->{'addr:city'} || $place->tags->{'contact:city'};
            }

            if ($place['tags']['phone']) {
                $phoneArray = $place['tags']['phone'] . match(reg);

                $phone = 0 . $phoneArray[1] . $phoneArray[2] . $phoneArray[3] . $phoneArray[4] . $phoneArray[5];
            } else if ($place['tags']['contact:phone']) {
                $phoneArray = $place['tags']['contact:phone'] . match(reg);

                $phone = 0 . $phoneArray[1] . $phoneArray[2] . $phoneArray[3] . $phoneArray[4] . $phoneArray[5];
            }
        }
    }
}
