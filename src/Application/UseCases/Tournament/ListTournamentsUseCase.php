<?php
namespace Src\Application\UseCases\Tournament;

use Src\Domain\Repositories\TournamentRepositoryInterface;

use Src\Application\DTOs\Tournament\ListTournamentsDTO;
use Src\Application\DTOs\Tournament\ListTournamentsResultDTO;
use Src\Domain\Collections\TournamentCollection;
use Src\Domain\Entities\Tournament;

class ListTournamentsUseCase
{
    public function __construct(
        private TournamentRepositoryInterface $tournamentRepository
    ) {}

    public function execute(ListTournamentsDTO $dto): TournamentCollection
    {
        $start_date = $dto->start_date ? $dto->start_date->format('Y-m-d') : null;
        $end_date = $dto->end_date ? $dto->end_date->format('Y-m-d') : null;
        $category_id = $dto->category ? $dto->category->value : null;
        $status_id = $dto->status ? $dto->status->value : null;

        return $this->tournamentRepository->findAll($start_date, $end_date, $category_id, $status_id);
    }
}