<?php
namespace Src\Domain\Repositories;

use Src\Domain\Entities\TournamentMatch;

interface TournamentMatchRepositoryInterface {
    //public function findAll(): array;
    public function save(TournamentMatch $tournament): TournamentMatch;
    //public function findById(int $id): ?TournamentMatch;
    //public function delete(int $id): bool;
}