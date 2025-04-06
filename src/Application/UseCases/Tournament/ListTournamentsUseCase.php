<?php
namespace Src\Application\UseCases\Tournament;

use Src\Domain\Repositories\TournamentRepositoryInterface;

class ListTournamentsUseCase
{
    public function __construct(
        private TournamentRepositoryInterface $tournamentRepository
    ) {}

    public function execute(): array
    {
        return $this->tournamentRepository->findAll();
    }
}