<?php
namespace Src\Domain\Entities;

use DateTimeImmutable;

use Src\Domain\Exceptions\FemalePlayerInMaleTournamentException;
use Src\Domain\Exceptions\MalePlayerInFemaleTournamentException;
class TournamentRegistration extends BaseEntity
{
    private Player $player;
    private Tournament $tournament;

    public function __construct(
        ?int $id,
        Player $player, 
        Tournament $tournament, 
        DateTimeImmutable $created_at = new DateTimeImmutable(),
        DateTimeImmutable $updated_at = new DateTimeImmutable()
    )
    {
        $this->setId($id);
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;

        $this->validatePlayerAndTournament($player, $tournament);
    }

    public function getTournament() : Tournament
    {
        return $this->tournament;
    }

    public function getPlayer() : Player
    {
        return $this->player;
    }

    private function validatePlayerAndTournament(
        Player $player, 
        Tournament $tournament
      ): void {
  
        if($tournament->getCategory()->isMens() && $player->getGender()->isFemale()) {
            throw new FemalePlayerInMaleTournamentException("Player ".$player->getId()." is a female player in a male tournament", 400);
        }
        else if($tournament->getCategory()->isWomens() && $player->getGender()->isMale()) {
            throw new MalePlayerInFemaleTournamentException("Player ".$player->getId()." is a male player in a female tournament", 400);
        }

        $this->player = $player;   
        $this->tournament = $tournament;
    }

    public function toArray(): array {
      return [
        'id' => $this->id,
        'player' => $this->getPlayer(),
        'tournament' => $this->getTournament(),
        'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
      ];
    }

    public function jsonSerialize(): array {
        return $this->toArray();
    }
}