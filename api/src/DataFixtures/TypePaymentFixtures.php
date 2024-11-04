<?php

namespace App\DataFixtures;

use App\Entity\TypePayment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TypePaymentFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $jsonFile = dirname(__DIR__) . '/../default_data/type_payment.json';

        if (!file_exists($jsonFile)) {
            throw new Exception("The JSON file does not exist: " . $jsonFile);
        }

        $jsonData = file_get_contents($jsonFile);
        $data     = json_decode($jsonData, true);

        foreach ($data as $index => $item) {
            $paymentType = new TypePayment();
            $paymentType->setName($item['name']);

            $this->addReference('type_payment_' . ++$index, $paymentType);

            $manager->persist($paymentType);
        }

        $manager->flush();
    }
}
