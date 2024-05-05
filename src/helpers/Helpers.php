<?php
namespace Ryosuwito\Usea\Helpers;
use Doctrine\ORM\EntityManager;
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class Helpers{
    private static EntityManager $entityManager;

    public function __construct()
    {
        require_once __DIR__ . '/../../bootstrap.php';
        self::$entityManager = $entityManager;
    }

    public static function getEntityManager(): EntityManager
    {
        return self::$entityManager;
    }

    public static function getPrayerTimes(string $prayerzone): array
    {
        // Prayer time can be retrieved from this government website https://www.e-solat.gov.my/.
        // API to retrieve prayer times, eg for zone JHR01, in JSON format:
        //     https://www.e-solat.gov.my/index.php?r=esolatApi/TakwimSolat&period=week&zone=JHR01

        // cache the result for today using symfony/cache
        $cacheKey = "prayerTime_$prayerzone";
        // strip reserved characters from cache key
        $cacheKey = preg_replace('/[^A-Za-z0-9_]/', '', $cacheKey);
        $cache = new FilesystemAdapter();
        $prayerTime = $cache->get($cacheKey, function (ItemInterface $item) use ($prayerzone) {
            $item->expiresAfter(3600);
            return self::fetchPrayerTime($prayerzone);
        });

        return $prayerTime;
    }

    // fetchPrayerTime function
    public static function fetchPrayerTime(string $prayerzone): array
    {
        $baseUrl = "https://www.e-solat.gov.my/index.php?r=esolatApi/TakwimSolat&period=week&zone=";
        $url = $baseUrl . $prayerzone;
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        if($data['status'] != "OK!") {
            echo "No record found for $prayerzone\n";
            return [];
        }
        if (!isset($data['prayerTime'])) {
            echo "No prayer time found for $prayerzone\n";
            return [];
        }
        return $data['prayerTime'];
    }

    // convert json time data to PrayerTime object
    public static function convertToPrayerTime(array $prayerTime)
    {
        $fajr = DateTime::createFromFormat('H:i:s', $prayerTime['fajr']);
        $dhuhr = DateTime::createFromFormat('H:i:s', $prayerTime['dhuhr']);
        $asr = DateTime::createFromFormat('H:i:s', $prayerTime['asr']);
        $maghrib = DateTime::createFromFormat('H:i:s', $prayerTime['maghrib']);
        $isha = DateTime::createFromFormat('H:i:s', $prayerTime['isha']);

        return [
            ['name' => 'Subuh', 'time' => $fajr, 'seq' => 1],
            ['name' => 'Zohor', 'time' => $dhuhr, 'seq' => 2],
            ['name' => 'Asar', 'time' => $asr, 'seq' => 3],
            ['name' => 'Maghrib', 'time' => $maghrib, 'seq' => 4],
            ['name' => 'Isyak', 'time' => $isha, 'seq' => 5]
        ];
    }

    // send email to ryosuwito@gmail.com
    public static function sendEmail(string $message)
    {
        $mail = new PHPMailer(true);
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
        $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
        $mail->Port = 465; // set the SMTP port for the GMAIL server
        // get from env file
        $mail->Username = $_ENV['GMAIL_USERNAME']; // GMAIL username
        $mail->Password = $_ENV['GMAIL_PASSWORD']; // GMAIL password

        $to = $_ENV['REPORT_EMAIL'];
        $recipient = $_ENV['RECIPIENT_NAME'];
        $sender = $_ENV['GMAIL_USERNAME'];
        $senderName = $_ENV['SENDER_NAME'];

        $mail->AddAddress($to, $recipient);
        $mail->SetFrom($sender, $senderName);
        $mail->Subject = "Prayer Time Error";
        $mail->Body = $message;

        try {
            $mail->Send();
            echo "Email sent to $to\n";
        } catch (\Exception $e) {
            echo "Email not sent. Error: " . $mail->ErrorInfo . "\n";
        }
    }
}