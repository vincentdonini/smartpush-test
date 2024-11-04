<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Transaction;
use Exception;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $jsonFile = dirname(__DIR__) . '/../default_data/transactions.json';

        if (!file_exists($jsonFile)) {
            throw new Exception("The JSON file does not exist: " . $jsonFile);
        }
        $jsonData = file_get_contents($jsonFile);
        $data     = json_decode($jsonData, true);

        foreach ($data as $item) {
            $transaction = new Transaction();
            $transaction->setLabel($item['label']);
            $transaction->setAmount($item['amount']);
            $transaction->setTypePayment($this->getReference('type_payment_'.$item['type_payment_id']));

            $manager->persist($transaction);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypePaymentFixtures::class,
        ];
    }
}
