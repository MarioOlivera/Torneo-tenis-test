<?php
namespace Src\Application\DTOs\Player;

use Src\Domain\Enums\TournamentCategory;
use Src\Domain\Enums\TournamentStatus;
use Src\Domain\Exceptions\ValidationException;

use DateTimeImmutable;

final class ListPlayersDTO {
    public function __construct(
        public readonly ?DateTimeImmutable $start_date,
        public readonly ?DateTimeImmutable $end_date,
        public readonly ?TournamentCategory $category,
        public readonly ?TournamentStatus $status
    ) {}

    public static function fromRequest(array $data): self {
        self::validate($data);

        return new self(
            start_date: isset($data['start_date']) && $data['start_date'] ? \DateTimeImmutable::createFromFormat('Y-m-d', $data['start_date']) : null,
            end_date: isset($data['end_date']) && $data['end_date']  ? \DateTimeImmutable::createFromFormat('Y-m-d', $data['end_date'])  : null,
            category: isset($data['category_id']) && $data['category_id'] ? TournamentCategory::from((int)$data['category_id']) : null,
            status: isset($data['status_id']) && $data['status_id'] ? TournamentStatus::from((int)$data['status_id']) : null
        );
    }

    private static function validate(array $data): void {
        $errors = [];

        if (isset($data['start_date']) && trim($data['start_date']) != "") {
            $d = \DateTimeImmutable::createFromFormat('Y-m-d', $data['start_date']);
            if (!$d || $d->format('Y-m-d') !== $data['start_date']) {
                $errors['start_date'] = 'start_date must be a valid date in Y-m-d format';
            }
        }

        if (isset($data['end_date']) && trim($data['end_date']) != "") {
            $d = \DateTimeImmutable::createFromFormat('Y-m-d', $data['end_date']);
            if (!$d || $d->format('Y-m-d') !== $data['end_date']) {
                $errors['end_date'] = 'end_date must be a valid date in Y-m-d format';
            }
        }

        if (isset($data['category_id']) && trim($data['category_id']) != "") {
            if (!is_numeric($data['category_id'])) {
                $errors['category_id'] = 'category_id must be a number';
            } elseif (!TournamentCategory::isValid((int)$data['category_id'])) {
                $errors['category_id'] = 'Invalid category_id';
            }
        }

        if (isset($data['status_id'])  && trim($data['status_id']) != "") {
            if (!is_numeric($data['status_id'])) {
                $errors['status_id'] = 'status_id must be a number';
            } elseif (!TournamentStatus::isValid((int)$data['status_id'])) {
                $errors['status_id'] = 'Invalid status_id';
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(json_encode($errors));
        }
    }
}
