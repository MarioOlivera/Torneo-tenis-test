<?php
namespace Src\Application\UseCases\Player;

use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Entities\Player;
use Src\Domain\Exceptions\PlayerNotFoundException;

class ShowPlayerUseCase
{
    public function __construct(
        private PlayerRepositoryInterface $playerRepository
    ) {}

    public function execute(int $id): Player
    {
        $player = $this->playerRepository->findById($id);

        if (!$player) {
            throw new PlayerNotFoundException("Player ".$id." not found", 404);
        }

        return $player;
    }
}