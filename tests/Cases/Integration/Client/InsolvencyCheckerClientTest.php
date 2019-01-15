<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Tests\Cases\Integration\Client;

use AsisTeam\ISIR\Client\InsolvencyCheckerClient;
use AsisTeam\ISIR\Client\InsolvencyCheckerClientFactory;
use AsisTeam\ISIR\Client\Request\Options;
use AsisTeam\ISIR\Enum\Relevancy;
use DateTimeImmutable;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class InsolvencyCheckerClientTest extends TestCase
{

	/** @var InsolvencyCheckerClient */
	private $client;

	public function setUp(): void
	{
		Environment::skip('This test should be run manually. Some assertions may not be currently valid.');

		$this->client = (new InsolvencyCheckerClientFactory())->create();
	}

	public function testCheckPersonById(): void
	{
		$client = (new InsolvencyCheckerClientFactory())->create();

		// without the slash
		$ins = $client->checkPersonById('6612276561', false);
		Assert::count(1, $ins);
		Assert::equal('661227/6561', $ins[0]->getPersonalId());

		// with the slash
		$ins = $client->checkPersonById('661227/6561', false);
		Assert::count(1, $ins);
		Assert::equal('661227/6561', $ins[0]->getPersonalId());

		// with active and historical records
		$ins = $client->checkPersonById('580519/2228', false);
		Assert::count(2, $ins);
		foreach ($ins as $i) {
			Assert::equal('580519/2228', $i->getPersonalId());
		}
	}

	public function testCheckPersonByName(): void
	{
		$ins = $this->client->checkPersonByName('Otto', 'Hruška', true);
		Assert::count(1, $ins);

		$ins = $this->client->checkPersonByName('Tomáš', 'Sedláček', false);
		Assert::count(9, $ins);

		$opts = new Options(3);
		$ins = $this->client->checkPersonByName('Tomáš', 'Sedláček', true, $opts);
		Assert::count(3, $ins);

		$opts = new Options(100, Relevancy::BY_NAME_SURNAME, true, true);
		$ins = $this->client->checkPersonByName('Tomáš', 'Sedláček', true, $opts);
		Assert::count(9, $ins);
	}

	public function testCheckPersonByNameAndBirth(): void
	{
		$ins = $this->client->checkPersonByNameAndBirth('Sedláček', new DateTimeImmutable('1994-06-03'), true);
		Assert::count(1, $ins);
	}

	public function testCheckCompanyById(): void
	{
		$ins = $this->client->checkCompanyById('27680339', true);
		Assert::count(1, $ins);
		Assert::equal('27680339', $ins[0]->getCompanyId());
	}

	public function testCheckCompanyByName(): void
	{
		$ins = $this->client->checkCompanyByName('SCF SERVIS, s.r.o', true);
		Assert::count(1, $ins);
		Assert::equal('27680339', $ins[0]->getCompanyId());
	}

	public function testCheckProceeding(): void
	{
		$ins = $this->client->checkProceeding(17712, 2017);
		Assert::count(1, $ins);
		Assert::equal(17712, $ins[0]->getRecordCommonNo());
		Assert::equal(2017, $ins[0]->getVintage());
	}

}

(new InsolvencyCheckerClientTest())->run();
