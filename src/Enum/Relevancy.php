<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Enum;

final class Relevancy
{

	// Vyhledáno podle rodného čísla
	public const BY_PERSONAL_ID = 1;

	// Vyhledáno podle identifikačního čísla osoby (IČO)
	public const BY_COMPANY_ID = 2;

	// Vyhledáno podle spisové značky
	public const BY_FILE_NUMBER = 3;

	// Vyhledáno podle příjmení, jména a data narození
	public const BY_NAME_SURNAME_BIRTHDATE = 4;

	// Vyhledáno podle příjmení a data narození
	public const BY_SURNAME_BIRTHDATE = 5;

	// Vyhledáno podle příjmení a jména
	public const BY_NAME_SURNAME = 6;

	// Vyhledáno podle názvu osoby (příjmení)
	public const BY_SURNAME = 7;

	public static function isValid(int $relevancy): bool
	{
		return $relevancy >= self::BY_PERSONAL_ID && $relevancy <= self::BY_SURNAME;
	}

}
