<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Client;

use AsisTeam\ISIR\Client\Request\Options;
use AsisTeam\ISIR\Client\Response\Hydrator;
use AsisTeam\ISIR\Entity\Insolvency;
use AsisTeam\ISIR\Exception\Runtime\RequestException;
use DateTimeImmutable;
use SoapClient;
use SoapFault;

final class InsolvencyCheckerClient
{

	/** @var SoapClient */
	private $client;

	/** @var Options|null */
	private $options;

	public function __construct(SoapClient $client, ?Options $clientOpts = null)
	{
		$this->client = $client;
		$this->options = $clientOpts;
	}

	public function checkProceeding(int $no, int $vintage, ?Options $opts = null): Insolvency
	{
		return $this->check(['bcVec' => $no, 'rocnik' => $vintage], $opts)[0];
	}

	public function checkCompanyById(string $companyId, ?Options $opts = null): Insolvency
	{
		return $this->check(['ic' => $companyId], $opts)[0];
	}

	public function checkPersonById(string $personId, ?Options $opts = null): Insolvency
	{
		return $this->check(['rc' => $personId], $opts)[0];
	}

	/**
	 * @return Insolvency[]
	 */
	public function checkCompanyByName(string $name, ?Options $opts = null): array
	{
		return $this->check(['nazevOsoby' => $name], $opts);
	}

	/**
	 * @return Insolvency[]
	 */
	public function checkPersonByName(string $firstname, string $lastname, ?Options $opts = null): array
	{
		return $this->check(['nazevOsoby' => $lastname, 'jmeno' => $firstname], $opts);
	}

	/**
	 * @return Insolvency[]
	 */
	public function checkPersonByNameAndBirth(
		string $lastname,
		DateTimeImmutable $birthday,
		?Options $opts = null
	): array
	{
		return $this->check(
			[
				'nazevOsoby'    => $lastname,
				'datumNarozeni' => $birthday->format('Y-m-d'),
			],
			$opts
		);
	}

	/**
	 * @param mixed[] $params
	 * @return Insolvency[]
	 */
	private function check(array $params, ?Options $reqOpts = null): array
	{
		try {
			$opts = $this->getOpts($reqOpts);
			$data = array_merge($params, $opts);

			// phpstan-ignore-next-line
			$resp = $this->client->getIsirWsCuzkData($data);

			return Hydrator::hydrate($resp);
		} catch (SoapFault $e) {
			throw new RequestException(
				sprintf('SOAP error %s. Request: %s', $e->getMessage(), $this->client->__getLastRequest()),
				0,
				$e
			);
		}
	}

	/**
	 * @return mixed[]
	 */
	private function getOpts(?Options $reqOpts = null): array
	{
		$opts = [];

		if ($this->options !== null) {
			$opts = $this->options->toArray();
		}

		if ($reqOpts !== null) {
			$opts = $reqOpts->toArray();
		}

		return $opts;
	}

}
