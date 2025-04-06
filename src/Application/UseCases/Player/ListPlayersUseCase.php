<?php
namespace Src\Application\UseCases\Player;

use Src\Domain\Repositories\PlayerRepositoryInterface;

class ListPlayersUseCase
{
    public function __construct(
        private PlayerRepositoryInterface $playerRepository
    ) {}

    public function execute(): array
    {
        return $this->playerRepository->findAll();
    }
}