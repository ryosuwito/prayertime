<?php
namespace Ryosuwito\Usea\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "songs")]
class Song{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;
    #[ORM\Column(type: "string")]
    private string $title;
    // foreign key to subs_id
    #[ORM\Column(type: "integer")]
    private int $subs_id;
    // foreign key to box_id
    #[ORM\Column(type: "integer")]
    private int $box_id;
    #[ORM\Column(type: "string")]
    private string $prayerzone;
    #[ORM\Column(type: "date")]
    private DateTime $prayertimedate;
    #[ORM\Column(type: "integer")]
    private int $prayerseq;
    // prayer time only without date
    #[ORM\Column(type: "time")]
    private DateTime $prayertime;

    // constructor
    public function __construct(string $title, string $prayerzone, DateTime $prayertimedate, int $prayerseq, DateTime $prayertime){
        $this->title = $title;
        $this->prayerzone = $prayerzone;
        $this->prayertimedate = $prayertimedate;
        $this->prayerseq = $prayerseq;
        $this->prayertime = $prayertime;
    }

    // get the box for this song
    #[ORM\ManyToOne(targetEntity: "Box", inversedBy: "songs")]
    #[ORM\JoinColumn(name: "box_id", referencedColumnName: "id")]
    private $box;

    // get the subscriber for this song via box
    #[ORM\ManyToOne(targetEntity: "Subscriber", inversedBy: "boxes")]
    #[ORM\JoinColumn(name: "subs_id", referencedColumnName: "id")]
    private $subscriber;

    // setters and getters for subscriber and box
    public function getSubscriber(){
        return $this->subscriber;
    }

    public function setSubscriber(Subscriber $subscriber){
        $this->subscriber = $subscriber;
    }

    public function getBox(){
        return $this->box;
    }

    public function setBox(Box $box){
        $this->box = $box;
    }
}