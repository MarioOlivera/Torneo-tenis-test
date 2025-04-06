<?php
namespace Src\Domain\Repositories;

use Src\Domain\Entities\TournamentRegistration;

interface TournamentRegistrationRepositoryInterface {
    public function save(TournamentRegistration $tournament_registration): TournamentRegistration;
}