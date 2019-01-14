<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Client\Request;

use AsisTeam\ISIR\Enum\Relevancy;
use AsisTeam\ISIR\Exception\Logical\InvalidArgumentException;

final class Options
{

	/** @var int */
	private $maxResultsCount;

	/** @var int */
	private $maxResultRelevancy;

	/** @var bool */
	private $exactNameMatch;

	/** @var bool */
	private $useDiacritics;

	public function __construct(
		?int $maxResultsCount = 200,
		?int $maxResultRelevancy = Relevancy::BY_SURNAME,
		?bool $exactNameMatch = false,
		?bool $useDiacritics = true
	)
	{
		$this->maxResultsCount = $maxResultsCount ?? 200;
		$this->exactNameMatch  = $exactNameMatch ?? false;
		$this->useDiacritics   = $useDiacritics ?? true;

		$this->setMaxResultRelevancy($maxResultRelevancy ?? Relevancy::BY_SURNAME);
	}

	public static function boolToStr(bool $val): string
	{
		return $val === true ? 'T' : 'F';
	}

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): self
	{
		return new self(
			isset($data['max_result_count']) ? (int) $data['max_result_count'] : null,
			isset($data['max_result_relevancy']) ? (int) $data['max_result_relevancy'] : null,
			isset($data['exact_name_match']) ? (bool) $data['exact_name_match'] : null,
			isset($data['use_diacritics']) ? (bool) $data['use_diacritics'] : null
		);
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'maxPocetVysledku'         => $this->getMaxResultsCount(),
			'maxRelevanceVysledku'     => $this->getMaxResultRelevancy(),
			'vyhledatPresnouShoduJmen' => self::boolToStr($this->isExactNameMatch()),
			'vyhledatBezDiakritiky'    => self::boolToStr($this->isUseDiacritics()),
		];
	}

	public function getMaxResultsCount(): int
	{
		return $this->maxResultsCount;
	}

	public function setMaxResultsCount(int $maxResultsCount): self
	{
		$this->maxResultsCount = $maxResultsCount;

		return $this;
	}

	public function getMaxResultRelevancy(): int
	{
		return $this->maxResultRelevancy;
	}

	public function setMaxResultRelevancy(int $maxResultRelevancy): self
	{
		if (!Relevancy::isValid($maxResultRelevancy)) {
			throw new InvalidArgumentException(sprintf('Given relevancy "%d" is invalid', $maxResultRelevancy));
		}

		$this->maxResultRelevancy = $maxResultRelevancy;

		return $this;
	}

	public function isExactNameMatch(): bool
	{
		return $this->exactNameMatch;
	}

	public function setExactNameMatch(bool $exactNameMatch): self
	{
		$this->exactNameMatch = $exactNameMatch;

		return $this;
	}

	public function isUseDiacritics(): bool
	{
		return $this->useDiacritics;
	}

	public function setUseDiacritics(bool $useDiacritics): self
	{
		$this->useDiacritics = $useDiacritics;

		return $this;
	}

}
