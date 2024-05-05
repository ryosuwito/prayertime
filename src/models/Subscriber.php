<?php
namespace Ryosuwito\Usea\Models;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "subscribers")]
class Subscriber{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;
    #[ORM\Column(type: "string")]
    private string $name;

    // id getter
    public function getId(): int{
        return $this->id;
    }

    public function getName(): string{
        return $this->name;
    }

    public function setName(string $name): void{
        $this->name = $name;
    }

    // get all boxes for this subscriber
    #[ORM\OneToMany(targetEntity: "Box", mappedBy: "subs_id")]
    protected $boxes;

    // get all songs for this subscriber
    #[ORM\OneToMany(targetEntity: "Song", mappedBy: "subs_id")]

    protected $songs;

    // get all songs for this subscriber

    public function getBoxes(){
        return $this->boxes;
    }

    public function getSongs(){
        return $this->songs;
    }

    public function addBox(Box $box){
        $this->boxes[] = $box;
    }

    public function addSong(Song $song){
        $this->songs[] = $song;
    }

    public function removeBox(Box $box){
        $this->boxes->removeElement($box);
    }

    public function removeSong(Song $song){
        $this->songs->removeElement($song);
    }

    public function __toString(){
        return $this->name;
    }
    
}