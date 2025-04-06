<?php
namespace Src\Application\UseCases\Player;

use Src\Application\DTOs\Player\CreatePlayerDTO;

use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Entities\Player;
use Src\Domain\Exceptions\ValidationException;

class CreatePlayerUseCase {
    public function __construct(
        private PlayerRepositoryInterface $repository
    ) {}

    public function execute(CreatePlayerDTO $dto): Player {
        try
        {
           $player = new Player(
                null, 
                $dto->name, 
                $dto->skill_level, 
                $dto->gender, 
                $dto->strength, 
                $dto->speed, 
                $dto->reaction_time
           );
        }
        catch(\InvalidArgumentException $e)
        {
            throw new ValidationException($e->getMessage());
        }

        return $this->repository->save($player);
    }
}