<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Client;

use AsisTeam\ISIR\Client\Request\Options;
use SoapClient;

final class InsolvencyCheckerClientFactory
{

	private const WSDL = __DIR__ . '/wsdl/IsirWsCuzkService.wsdl';

	/** @var Options|null */
	private $opts;

	public function __construct(?Options $opts = null)
	{
		$this->opts = $opts;
	}

	public function create(): InsolvencyCheckerClient
	{
		$soap = new SoapClient(
			self::WSDL,
			[
				'encoding'   => 'UTF-8',
				'trace'      => true,
				'cache_wsdl' => WSDL_CACHE_NONE,
			]
		);

		return new InsolvencyCheckerClient($soap, $this->opts);
	}

}
