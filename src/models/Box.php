<?php
namespace Ryosuwito\Usea\Models;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "boxes")]
class Box{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;
    #[ORM\Column(type: "string")]
    private string $name;
    #[ORM\Column(type: "string")]
    private string $prayerzone;

    // optional use_prayertime option
    #[ORM\Column(type: "boolean")]
    private bool $use_prayertime = false;

    // foreign key to subs_id
    #[ORM\Column(type: "integer")]
    private int $subs_id;

    // get all songs for this box
    #[ORM\OneToMany(targetEntity: "Song", mappedBy: "box_id")]
    private $songs;

    // get the subscriber for this box
    #[ORM\ManyToOne(targetEntity: "Subscriber", inversedBy: "boxes")]
    #[ORM\JoinColumn(name: "subs_id", referencedColumnName: "id")]
    private $subscriber;

    public function getId(): int{
        return $this->id;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getUsePrayertime(): bool{
        return $this->use_prayertime;
    }

    public function setUsePrayertime(bool $use_prayertime): void{
        $this->use_prayertime = $use_prayertime;
    }

    public function setName(string $name): void{
        $this->name = $name;
    }

    public function getPrayerzone(): string{
        return $this->prayerzone;
    }

    public function setPrayerzone(string $prayerzone): void{
        $this->prayerzone = $prayerzone;
    }

    public function getSubscriber(): Subscriber{
        return $this->subscriber;
    }

    public function setSubscriber(Subscriber $subscriber): void{
        $this->subscriber = $subscriber;
    }

    public function getSongs(): array{
        return $this->songs;
    }

    public function setSongs(array $songs): void{
        $this->songs = $songs;
    }

    public function getSubsId(): int{
        return $this->subs_id;
    }

    public function setSubsId(int $subs_id): void{
        $this->subs_id = $subs_id;
    }

    public function addSong(Song $song): void{
        $this->songs[] = $song;
    }

    public function removeSong(Song $song): void{
        $key = array_search($song, $this->songs);
        if($key !== false){
            unset($this->songs[$key]);
        }
    }

    public function __toString(): string{
        return $this->name;
    }
}