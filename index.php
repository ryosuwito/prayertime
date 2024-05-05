<?php
    require_once "vendor/autoload.php";
    use Ryosuwito\Usea\Models\Box;
    use Ryosuwito\Usea\Models\Song;
    use Ryosuwito\Usea\Helpers\Helpers;

    // we will run script to fetch data and create songs from entities
    function updatePrayerTime(){
        $helper = new Helpers();
        $entityManager = $helper->getEntityManager();
        // If the box has prayer time enabled, then it will have a voice over which is played in prayer time
        // every day. For example, in date 18 October 2023, in prayer zone JHR01, voice over will be played
        // in these time
    
        // - The script shall update 7 days of voiceovers in advance. For example, today is 01/03/2024, it will
        // generate prayer time from today to 07/03/2024
    
        // get all boxes with use_prayertime = true
        $boxes = $entityManager->getRepository(Box::class)->findBy(['use_prayertime' => true]);
        // get prayer times for each box

        // for bulk insertion
        $batchSize = 5 * 7;
        $currentBatch = 0;
        foreach ($boxes as $box) {
            // get subscriber
            $subscriber = $box->getSubscriber();
            // get prayer zone
            $prayerzone = $box->getPrayerzone();
            // get prayer times for the next 7 days
            try {
                $prayerTimes = $helper->getPrayerTimes($prayerzone);
            } catch (Exception $e) {
                echo "Skipping $prayerzone\n";
                // send email
                $helper->sendEmail("
                    Box ID: {$box->getId()}
                    Prayer Time Zone: $prayerzone
                    Error Message: {$e->getMessage()}
                ");
                continue;
            }

            // If there are any errors, send an email to phu@expressinmusic.com. The email needs to have a box
            // id, prayer time zone, and error message.
            if(empty($prayerTimes)) {
                echo "Skipping $prayerzone\n";
                // send email
                $helper->sendEmail("
                    Box ID: {$box->getId()}
                    Prayer Time Zone: $prayerzone
                    Error Message: No prayer time found
                ");
                continue;
            } else {
                echo "Processing Box ID: {$box->getId()} Date: {$prayerTimes[0]['date']}\n";
            }
            foreach ($prayerTimes as $prayerTime) {
                // batch insertions
                $date = DateTime::createFromFormat('d-M-Y', $prayerTime['date']);
                
                // check if we already have songs for this date
                $songs = $entityManager->getRepository(Song::class)->findBy([
                    'prayertimedate' => $date,
                    'prayerzone' => $prayerzone,
                    'subscriber' => $subscriber,
                    'box' => $box
                ]);

                if (!empty($songs)) {
                    echo "Songs already exist\n";
                    $date->add(new DateInterval("P1D"));
                    continue;
                }

                $time = $helper->convertToPrayerTime($prayerTime);

                foreach ($time as $t) {
                    $currentBatch++;
                    $song = new Song(
                        "{$t['name']} ({$date->format('d-m')})",
                        $prayerzone,
                        $date,
                        $t['seq'],
                        $t['time']
                    );
                    $song->setSubscriber($subscriber);
                    $song->setBox($box);
                    $entityManager->persist($song);
                }
            }
            if ($currentBatch % $batchSize == 0) {
                $entityManager->flush();
                $entityManager->clear();
            }
        }
    }
    updatePrayerTime();
    echo "Prayer time updated Successfully\n"
?>