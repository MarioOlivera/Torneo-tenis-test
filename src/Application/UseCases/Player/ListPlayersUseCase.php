<?php
namespace Src\Application\UseCases\Player;

use Src\Domain\Collections\PlayerCollection;
use Src\Domain\Repositories\PlayerRepositoryInterface;

class ListPlayersUseCase
{
    public function __construct(
        private PlayerRepositoryInterface $playerRepository
    ) {}

    public function execute(): PlayerCollection
    {
        return $this->playerRepository->findAll();
    }
}