<?php

namespace App\Command;

use App\Entity\Place;
use App\Services\ImportData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDataCommand extends Command
{
    protected $importData;

    protected static $defaultName = 'app:import-data';

    public function __construct(string $name = null, ImportData $importData)
    {
        parent::__construct($name);

        $this->importData = $importData;
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
        $places = json_decode($jsonPlaces, true);

        $this->importData->deleteData();

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
            $regPhone = '/^(?:(?:\+|00)33|0)\s*([1-9])(?:[\s.-]*(\d{2}))(?:[\s.-]*(\d{2}))(?:[\s.-]*(\d{2}))(?:[\s.-]*(\d{2}))$/';


            // Import Address
            if ((isset($place['tags']['addr:housenumber']) && isset($place['tags']['addr:street']))) {
                $address = $place['tags']['addr:housenumber'] . ' ' . $place['tags']['addr:street'];
            } elseif ((isset($place['tags']['contact:housenumber']) && isset($place['tags']['contact:street']))) {
                $address = $place['tags']['contact:housenumber'] . ' ' . $place['tags']['contact:street'];
            } elseif (isset($place['tags']['addr:street'])) {
                $address = $place['tags']['addr:street'];
            } elseif (isset($place['tags']['contact:street'])) {
                $address = $place['tags']['contact:street'];
            }


            // Import City
            if (isset($place['tags']['addr:city'])) {
                $city = $place['tags']['addr:city'];
            } elseif (isset($place['tags']['contact:city'])) {
                $city = $place['tags']['contact:city'];
            }


            // Import Phone Number
            if (isset($place['tags']['phone'])) {
                preg_match($regPhone, $place['tags']['phone'], $phoneArray);
                $phone = 0 . $phoneArray[1] . $phoneArray[2] . $phoneArray[3] . $phoneArray[4] . $phoneArray[5];
            } else if (isset($place['tags']['contact:phone'])) {
                preg_match($regPhone, $place['tags']['contact:phone'], $phoneArray);
                $phone = 0 . $phoneArray[1] . $phoneArray[2] . $phoneArray[3] . $phoneArray[4] . $phoneArray[5];
            }


            // Import Email
            if (isset($place['tags']['email'])) {
                $email = $place['tags']['email'];
            } elseif (isset($place['tags']['contact:email'])) {
                $email = $place['tags']['contact:email'];
            }


            // Import Website
            if (isset($place['tags']['website'])) {
                $website = $place['tags']['website'];
            } elseif (isset($place['tags']['contact:website'])) {
                $website = $place['tags']['contact:website'];
            } elseif (isset($place['tags']['brand:website'])) {
                $website = $place['tags']['brand:website'];
            }


            // Import Cuisines
            if (isset($place['tags']['cuisine'])) {
                //$cuisine = explode(';', $place['tags']['cuisine']);
                $cuisine = str_replace(';', ', ', $place['tags']['cuisine'] );
            }


            // Import Opening Hours
            if (isset($place['tags']['opening_hours'])) {
                $days = [
                    'Mo' => 'Lu',
                    'Tu' => 'Ma',
                    'We' => 'Me',
                    'Th' => 'Je',
                    'Fr' => 'Ve',
                    'Sa' => 'Sa',
                    'Su' => 'Di',
                ];

                preg_match_all('/Mo|Tu|We|Th|Fr|Sa|Su/i', $place['tags']['opening_hours'], $matches );

                $openingHours = $place['tags']['opening_hours'];
                foreach ($matches[0] as $match) {
                    $openingHours = str_replace($match, $days[ucfirst($match)], $openingHours);
                }
            }

            $test = [
                $address,
                $city,
                $phone,
                $email,
                $website,
                $openingHours,
                $cuisine,
            ];

            $restaurant->setName($place['tags']['name']);
            $restaurant->setLat($place['lat']);
            $restaurant->setLng($place['lon']);
            $restaurant->setType($place['tags']['amenity']);
            $restaurant->setAddress($address);
            $restaurant->setCity($city);
            $restaurant->setPhone($phone);
            $restaurant->setEmail($email);
            $restaurant->setWebsite($website);
            $restaurant->setOpeningHours($openingHours);
            $restaurant->setCuisine($cuisine);
            $restaurant->setCreatedAt(new \DateTime());
            $restaurant->setUpdatedAt(new \DateTime());

            $this->importData->importData($restaurant);
        }
        $output->writeln("Place successfully added to the database.");
    }
}
