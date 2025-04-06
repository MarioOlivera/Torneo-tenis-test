<?php
namespace Src\Domain\Repositories;

use Src\Domain\Entities\Player;

interface PlayerRepositoryInterface {
    public function findAll(): array;
    public function save(Player $player): Player;
    public function findById(int $id): ?Player;
    public function delete(int $id): bool;
}