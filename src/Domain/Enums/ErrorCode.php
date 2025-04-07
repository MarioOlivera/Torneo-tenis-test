<?php
namespace Src\Domain\Enums;

enum ErrorCode: int {
    case GENERAL = 1;
    case VALIDATION = 2;
    case TOURNAMENT_NOT_FOUND = 3;
    case TOURNAMENT_IS_NOT_PENDING = 4;
    case TOURNAMENT_NOT_PLAYERS_REGISTERED = 5;
    case TOURNAMENT_INVALID_PLAYERS_COUNT = 6;
    case NOT_FOUND_RESOURCE = 7;
    case PLAYER_NOT_FOUND = 8;
    case MALE_PLAYER_IN_FEMALE_TOURNAMENT = 9;
    case FEMALE_PLAYER_IN_MALE_TOURNAMENT = 10;
    case MALE_PLAYERS_REQUIRE_STRENGTH_AND_SPEED = 11;
    case FEMALE_PLAYERS_REQUIRE_REACTION_TIME = 12;
    case INVALID_PLAYER_NAME = 13;
    case SKILL_LEVEL_OUT_OF_RANGE = 14;
    case STRENGTH_OUT_OF_RANGE = 15;
    case SPEED_OUT_OF_RANGE = 16;
    case REACTION_TIME_OUT_OF_RANGE = 17;
    case PLAYER_REGISTERED_IN_PENDING_TOURNAMENT = 18;

    public function description(): string {
        return match($this) {
            self::GENERAL => "General error",
            self::VALIDATION => "Validation error",
            self::TOURNAMENT_NOT_FOUND => "Tournament not found",
            self::TOURNAMENT_IS_NOT_PENDING => "Tournament is not pending",
            self::TOURNAMENT_NOT_PLAYERS_REGISTERED => "Tournament not players registered",
            self::TOURNAMENT_INVALID_PLAYERS_COUNT => "Tournament invalid players count",
            self::NOT_FOUND_RESOURCE => "Resource not found",
            self::PLAYER_NOT_FOUND => "Player not found",
            self::MALE_PLAYER_IN_FEMALE_TOURNAMENT => "Male player in female tournament",
            self::FEMALE_PLAYER_IN_MALE_TOURNAMENT => "Female player in male tournament",
            self::MALE_PLAYERS_REQUIRE_STRENGTH_AND_SPEED => "Male players require strength and speed",
            self::FEMALE_PLAYERS_REQUIRE_REACTION_TIME => "Female players require reaction time",
            self::INVALID_PLAYER_NAME => "Invalid player name",
            self::SKILL_LEVEL_OUT_OF_RANGE => "Skill level out of range",
            self::STRENGTH_OUT_OF_RANGE => "Strength out of range",
            self::SPEED_OUT_OF_RANGE => "Speed out of range",
            self::REACTION_TIME_OUT_OF_RANGE => "Reaction time out of range",
            self::PLAYER_REGISTERED_IN_PENDING_TOURNAMENT => "Player registered in pending tournament",
        };
    }
}