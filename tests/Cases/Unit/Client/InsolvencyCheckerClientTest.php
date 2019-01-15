<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Tests\Cases\Unit\Client;

use AsisTeam\ISIR\Client\InsolvencyCheckerClient;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class InsolvencyCheckerClientTest extends TestCase
{

	public function testCheckByCompanyId(): void
	{
		$client = new InsolvencyCheckerClient(SoapMockHelper::createSoapMock('by_company_id.xml'));
		$ins = $client->checkCompanyById('27680339', true);

		Assert::count(1, $ins);
		$ins = $ins[0];

		Assert::equal('Krajský soud v Brně, 40 INS 11095 / 2018', $ins->headerToString());
		Assert::equal('SÍDLO FY, J. Fučíka 308, Holasice 664 61', $ins->addressToString());
		Assert::equal('ID: 27680339, SCF SERVIS, s.r.o.', $ins->subjectToString());
		Assert::contains('http', $ins->getUrl());
		Assert::contains('REORGANIZ', $ins->getBankruptcyState());
		Assert::contains('F', $ins->getAnotherDebtor());
	}

	public function testCheckByPersonalId(): void
	{
		$client = new InsolvencyCheckerClient(SoapMockHelper::createSoapMock('by_personal_id.xml'));
		$ins = $client->checkCompanyById('661227/1234', true); // same as '6612271234'

		Assert::count(1, $ins);
		$ins = $ins[0];

		Assert::equal('Krajský soud v Praze, 41 INS 17502 / 2012', $ins->headerToString());
		Assert::equal('TRVALÁ, Havlíčkova 53, Zbyslav 286 01', $ins->addressToString());
		Assert::equal('ID: 661227/1234, Josef Bolvan', $ins->subjectToString());
		Assert::contains('http', $ins->getUrl());
		Assert::contains('ODSKRTNUTA', $ins->getBankruptcyState());
		Assert::contains('F', $ins->getAnotherDebtor());
	}

	public function testCheckByPersonalIdHasMultiple(): void
	{
		$client = new InsolvencyCheckerClient(SoapMockHelper::createSoapMock('by_personal_id_multiple.xml'));
		$ins = $client->checkCompanyById('580519/2228', true); // same as '5805192228'

		Assert::count(2, $ins);
	}

	public function testCheckByPersonName(): void
	{
		$client = new InsolvencyCheckerClient(SoapMockHelper::createSoapMock('by_person_name.xml'));
		$ins = $client->checkPersonByName('Tomáš', 'Sedláček', true);

		Assert::count(9, $ins);
		foreach ($ins as $i) {
			// always CID od PID or both must be present
			Assert::true($i->getCompanyId() !== null || $i->getPersonalId() !== null);
		}
	}

	public function testCheckProceeding(): void
	{
		$client = new InsolvencyCheckerClient(SoapMockHelper::createSoapMock('by_id_vintage.xml'));
		$ins = $client->checkProceeding(17712, 2017);

		Assert::count(1, $ins);
		$ins = $ins[0];

		Assert::equal('Krajský soud v Brně, 37 INS 17712 / 2017', $ins->headerToString());
		Assert::equal('TRVALÁ, U Staré školy 217, Dobronín 588 12', $ins->addressToString());
		Assert::equal('ID: 850117/4770, Tomáš Sedláček', $ins->subjectToString());
	}

	public function testCheckEmptyDataError(): void
	{
		$client = new InsolvencyCheckerClient(SoapMockHelper::createSoapMock('error_ws2.xml'));
		Assert::equal([], $client->checkCompanyById('123456789', true));
		Assert::equal([], $client->checkPersonByName('Tomas', 'Sedlacek', true));
	}

	/**
	 * @throws AsisTeam\ISIR\Exception\Runtime\InvalidParamsCombinationException
	 */
	public function testCheckInvalidRequestError(): void
	{
		$client = new InsolvencyCheckerClient(SoapMockHelper::createSoapMock('error_ws1.xml'));
		$client->checkCompanyById('123456789', true);
	}

}

(new InsolvencyCheckerClientTest())->run();
