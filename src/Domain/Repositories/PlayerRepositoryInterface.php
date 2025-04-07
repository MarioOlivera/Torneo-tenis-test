<?php
namespace Src\Domain\Repositories;

use Src\Domain\Collections\PlayerCollection;
use Src\Domain\Entities\Player;

interface PlayerRepositoryInterface {
    public function findAll(): PlayerCollection;
    public function save(Player $player): Player;
    public function findById(int $id): ?Player;
    public function delete(int $id): bool;
    public function findPlayersByTournamentId(int $tournamentId): array;
}