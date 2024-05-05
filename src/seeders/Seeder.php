<?php
namespace Ryosuwito\Usea\Seeders;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Ryosuwito\Usea\models\Box;
use Ryosuwito\Usea\models\Subscriber;
use Ryosuwito\Usea\models\Song;

// use fzaninotto/faker
use Faker\Factory;

require __DIR__ . '/../../bootstrap.php';

$faker = Factory::create();

// seed subscribers
for ($i = 0; $i < 10; $i++) {
    $subscriber = new Subscriber();
    $subscriber->setName($faker->name);
    $entityManager->persist($subscriber);
    $entityManager->flush();
}

// seed boxes
$subscribers = $entityManager->getRepository(Subscriber::class)->findAll();
foreach ($subscribers as $subscriber) {
    //array of prayerzone in malay
    $prayerzones = [
        'JHR01',
        'JHR02',
        'JHR03',
        'JHR04',
        'KDH01',
        'KDH02',
        'KDH03',
        'KDH04',
        'KDH05',
        'KDH06',
        'KDH07',
        'KTN01',
        'KTN03',
        'MLK01',
        'NGS01',
        'NGS02'
    ];
    // randomly pick 1-3 boxes for each subscriber
    $maxBoxes = rand(1, 3);
    // batch insetions
    $batchSize = 20;
    $currentBatch = 0;
    for ($i = 0; $i < $maxBoxes; $i++) {
        $box = new Box();
        $box->setName($faker->company);
        $box->setPrayerzone($prayerzones[array_rand($prayerzones)]);
        $box->setSubscriber($subscriber);
        // box can randomny use prayertime
        $box->setUsePrayertime($faker->boolean);
        $entityManager->persist($box);
        $currentBatch++;
        if ($currentBatch % $batchSize == 0) {
            $entityManager->flush();
            $entityManager->clear();

            // refresh the subscriber
            $subscriber = $entityManager->getRepository(Subscriber::class)->find($subscriber->getId());
        }
    }
    // flush remaining boxes
    $entityManager->flush();
}
