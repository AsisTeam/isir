<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Entity;

use DateTimeImmutable;

final class Insolvency
{

	/** @var string|null */
	private $companyId;

	/** @var int|null */
	private $senateNo;

	/** @var string|null */
	private $recordType;

	/** @var int|null */
	private $vintage;

	/** @var int|null */
	private $recordCommonNo;

	/** @var string|null */
	private $organization;

	/** @var string|null */
	private $personalId;

	/** @var DateTimeImmutable|null */
	private $birth;

	/** @var string|null */
	private $titlePrefix;

	/** @var string|null */
	private $titleSuffix;

	/** @var string|null */
	private $name;

	/** @var string|null */
	private $subjectName;

	/** @var string|null */
	private $addressType;

	/** @var string|null */
	private $municipality;

	/** @var string|null */
	private $street;

	/** @var string|null */
	private $streetNo;

	/** @var string|null */
	private $region;

	/** @var string|null */
	private $country;

	/** @var string|null */
	private $zip;

	/** @var string|null */
	private $bankruptcyState;

	/** @var DateTimeImmutable|null */
	private $syncDate;

	/** @var string|null */
	private $url;

	/** @var string|null */
	private $anotherDebtor;

	/** @var int|null */
	private $resultsCount;

	/** @var int|null */
	private $relevancy;

	/**
	 * @param mixed[] $data
	 */
	public static function fromArray(array $data): self
	{
		$i = new self();

		$i->companyId       = isset($data['ic']) ? (string) $data['ic'] : null;
		$i->senateNo        = isset($data['cisloSenatu']) ? (int) $data['cisloSenatu'] : null;
		$i->recordType      = isset($data['druhVec']) ? (string) $data['druhVec'] : null;
		$i->vintage         = isset($data['rocnik']) ? (int) $data['rocnik'] : null;
		$i->recordCommonNo  = isset($data['bcVec']) ? (int) $data['bcVec'] : null;
		$i->organization    = isset($data['nazevOrganizace']) ? (string) $data['nazevOrganizace'] : null;
		$i->personalId      = isset($data['rc']) ? (string) $data['rc'] : null;
		$i->titlePrefix     = isset($data['titulPred']) ? (string) $data['titulPred'] : null;
		$i->titleSuffix     = isset($data['titulZa']) ? (string) $data['titulZa'] : null;
		$i->name            = isset($data['jmeno']) ? (string) $data['jmeno'] : null;
		$i->subjectName     = isset($data['nazevOsoby']) ? (string) $data['nazevOsoby'] : null;
		$i->addressType     = isset($data['druhAdresy']) ? (string) $data['druhAdresy'] : null;
		$i->municipality    = isset($data['mesto']) ? (string) $data['mesto'] : null;
		$i->street          = isset($data['ulice']) ? (string) $data['ulice'] : null;
		$i->streetNo        = isset($data['cisloPopisne']) ? (string) $data['cisloPopisne'] : null;
		$i->region          = isset($data['okres']) ? (string) $data['okres'] : null;
		$i->country         = isset($data['zeme']) ? (string) $data['zeme'] : null;
		$i->zip             = isset($data['psc']) ? (string) $data['psc'] : null;
		$i->bankruptcyState = isset($data['druhStavKonkursu']) ? (string) $data['druhStavKonkursu'] : null;
		$i->resultsCount    = isset($data['pocetVysledku']) ? (int) $data['pocetVysledku'] : null;
		$i->relevancy       = isset($data['relevanceVysledku']) ? (int) $data['relevanceVysledku'] : null;
		$i->url             = isset($data['urlDetailRizeni']) ? (string) $data['urlDetailRizeni'] : null;
		$i->anotherDebtor   = isset($data['dalsiDluznikVRizeni']) ? (string) $data['dalsiDluznikVRizeni'] : null;

		$i->birth    = isset($data['datumNarozeni']) ?
			new DateTimeImmutable((string) $data['datumNarozeni']) : null;
		$i->syncDate = isset($data['casSynchronizace']) ?
			new DateTimeImmutable((string) $data['casSynchronizace']) : null;

		return $i;
	}

	public function headerToString(): string
	{
		$p = '';

		$p .= $this->organization !== null ? $this->organization . ', ' : '';
		$p .= $this->senateNo !== null ? $this->senateNo . ' ' : '';
		$p .= $this->recordType !== null ? $this->recordType . ' ' : '';
		$p .= $this->recordCommonNo !== null ? $this->recordCommonNo . ' / ' : '';
		$p .= $this->vintage ?? '';

		return $p;
	}

	public function addressToString(): string
	{
		$a = '';

		$a .= $this->addressType !== null ? $this->addressType . ', ' : '';
		$a .= $this->street !== null ? $this->street . ' ' : '';
		$a .= $this->streetNo !== null ? $this->streetNo . ', ' : '';
		$a .= $this->municipality !== null ? $this->municipality . ' ' : '';
		$a .= $this->zip ?? '';
		$a .= $this->region !== null ? ', ' . $this->region : '';
		$a .= $this->country !== null ? ', ' . $this->country : '';

		return $a;
	}

	public function subjectToString(): string
	{
		$s = 'ID: ';

		if ($this->companyId !== null) {
			$s .= $this->companyId ?? '';
			$s .= $this->subjectName !== null ? ', ' . $this->subjectName : '';
		}

		if ($this->personalId !== null) {
			$s .= $this->personalId ?? '';
			$s .= $this->name !== null ? ', ' . $this->name : '';
			$s .= $this->subjectName !== null ? ' ' . $this->subjectName : '';
		}

		return $s;
	}

	public function getCompanyId(): ?string
	{
		return $this->companyId;
	}

	public function getSenateNo(): ?int
	{
		return $this->senateNo;
	}

	public function getRecordType(): ?string
	{
		return $this->recordType;
	}

	public function getVintage(): ?int
	{
		return $this->vintage;
	}

	public function getRecordCommonNo(): ?int
	{
		return $this->recordCommonNo;
	}

	public function getOrganization(): ?string
	{
		return $this->organization;
	}

	public function getPersonalId(): ?string
	{
		return $this->personalId;
	}

	public function getBirth(): ?DateTimeImmutable
	{
		return $this->birth;
	}

	public function getTitlePrefix(): ?string
	{
		return $this->titlePrefix;
	}

	public function getTitleSuffix(): ?string
	{
		return $this->titleSuffix;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getSubjectName(): ?string
	{
		return $this->subjectName;
	}

	public function getAddressType(): ?string
	{
		return $this->addressType;
	}

	public function getMunicipality(): ?string
	{
		return $this->municipality;
	}

	public function getStreet(): ?string
	{
		return $this->street;
	}

	public function getStreetNo(): ?string
	{
		return $this->streetNo;
	}

	public function getRegion(): ?string
	{
		return $this->region;
	}

	public function getCountry(): ?string
	{
		return $this->country;
	}

	public function getZip(): ?string
	{
		return $this->zip;
	}

	public function getBankruptcyState(): ?string
	{
		return $this->bankruptcyState;
	}

	public function getSyncDate(): ?DateTimeImmutable
	{
		return $this->syncDate;
	}

	public function getUrl(): ?string
	{
		return $this->url;
	}

	public function getAnotherDebtor(): ?string
	{
		return $this->anotherDebtor;
	}

	public function getResultsCount(): ?int
	{
		return $this->resultsCount;
	}

	public function getRelevancy(): ?int
	{
		return $this->relevancy;
	}

}
