<?php
namespace Src\Domain\Repositories;

use Src\Domain\Entities\Tournament;

interface TournamentRepositoryInterface {
    //public function findAll(): array;
    //public function save(Tournament $tournament): Tournament;
    public function findById(int $id): ?Tournament;
    //public function delete(int $id): bool;
    public function updateStatus(int $id, int $status_id) : bool;
}