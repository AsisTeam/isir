<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Bridges\Nette\DI;

use AsisTeam\ISIR\Client\InsolvencyCheckerClientFactory;
use AsisTeam\ISIR\Client\Request\Options;
use AsisTeam\ISIR\Enum\Relevancy;
use Nette\DI\CompilerExtension;

class ISIRExtension extends CompilerExtension
{

	/** @var mixed[] */
	public $defaults = [
		'max_result_count'        => 200,
		'max_result_relevancy'    => Relevancy::BY_SURNAME,
		'active_proceedings_only' => false,
		'exact_name_match'        => false,
		'use_diacritics'          => true,
	];

	/**
	 * @inheritDoc
	 */
	public function loadConfiguration(): void
	{
		$config  = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('insolvency_checker_factory'))
			->setFactory(InsolvencyCheckerClientFactory::class, [Options::fromArray($config)]);
	}

}
