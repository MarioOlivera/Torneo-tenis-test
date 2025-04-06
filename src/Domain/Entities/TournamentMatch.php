<?php
namespace Src\Domain\Entities;

use DateTimeImmutable;

class TournamentMatch
{
    private int $id;
    private Tournament $tournament;
    private Player $player_one;
    private Player $player_two;
    private Player $player_winner;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;

    public function __construct(
        ?int $id,
        Tournament $tournament, 
        Player $player_one, 
        Player $player_two, 
        Player $player_winner, 
        DateTimeImmutable $created_at = new DateTimeImmutable(),
        DateTimeImmutable $updated_at = new DateTimeImmutable()
    )
    {
        $this->setId($id);
        $this->tournament = $tournament;
        $this->player_one = $player_one;
        $this->player_two = $player_two;
        $this->setPlayerWinner($player_winner);
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int { return $this->id; }
    private function setId(?int $id): void { $this->id = $id; $this->setUpdatedAt(new DateTimeImmutable()); }

    public function getTournament() : Tournament
    {
        return $this->tournament;
    }

    public function getPlayerOne() : Player
    {
        return $this->player_one;
    }

    public function getPlayerTwo() : Player
    {
        return $this->player_two;
    }

    public function setPlayerWinner(?Player $player_winner)
    {
        if ($player_winner !== null && !$this->isPlayerInMatch($player_winner)) {
            throw new \InvalidArgumentException("Winner must be one of the match players.");
        }

        $this->player_winner = $player_winner;
        $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function getPlayerWinner() : Player
    {
        return $this->player_winner;
    }

    public function getCreatedAt(): DateTimeImmutable { return $this->created_at; }

    private function setUpdatedAt(DateTimeImmutable $updated_at) : void { $this->updated_at = $updated_at; }
    public function getUpdatedAt(): DateTimeImmutable { return $this->updated_at; }

    private function isPlayerInMatch(Player $player): bool {
        return $player->getId() === $this->player_one->getId() || 
               $player->getId() === $this->player_two->getId();
    }
}