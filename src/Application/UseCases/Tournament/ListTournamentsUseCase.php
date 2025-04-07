<?php
namespace Src\Application\UseCases\Tournament;

use Src\Domain\Repositories\TournamentRepositoryInterface;

use Src\Application\DTOs\Player\ListPlayersDTO;

class ListTournamentsUseCase
{
    public function __construct(
        private TournamentRepositoryInterface $tournamentRepository
    ) {}

    public function execute(ListPlayersDTO $dto): array
    {
        $start_date = $dto->start_date ? $dto->start_date->format('Y-m-d') : null;
        $end_date = $dto->end_date ? $dto->end_date->format('Y-m-d') : null;
        $category_id = $dto->category ? $dto->category->value : null;
        $status_id = $dto->status ? $dto->status->value : null;

        return $this->tournamentRepository->findAll($start_date, $end_date, $category_id, $status_id);
    }
}