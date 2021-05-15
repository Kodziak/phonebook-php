<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
{
    $data = file_get_contents("src/DataFixtures/data.json");
    if ($data === false) {
        echo "Data file not found.";
    }

    $json_a = json_decode($data, true);
    if ($json_a === null) {
        echo "Decode file failed.";
    }

    foreach ($json_a as $person_name => $shop) {
        $customer = new Customer();

        $customer->setName('Apple Store');
        $customer->setPhoneNumber($shop['phoneNumber']);
        $customer->setStreet($shop['street']);
        $customer->setPostCode($shop['postCode']);
        $customer->setCity($shop['city']);
        $customer->setLongitude($shop['lon']);
        $customer->setLatitude($shop['lat']);

        $manager->persist($customer);
    }

    $manager->flush();
}
}

