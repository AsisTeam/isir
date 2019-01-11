<?php declare(strict_types = 1);

namespace AsisTeam\ISIR\Client\Response;

use AsisTeam\ISIR\Entity\Insolvency;
use AsisTeam\ISIR\Exception\Runtime\InvalidParamsCombinationException;
use AsisTeam\ISIR\Exception\Runtime\NoRecordFoundException;
use AsisTeam\ISIR\Exception\Runtime\ResponseException;
use stdClass;

final class Hydrator
{

	private const ERROR_INVALID_PARAMS_COMBINATION = 'WS1';
	private const ERROR_EMPTY_DATA                 = 'WS2';
	private const ERROR_NAME_TOO_SHORT             = 'WS3';

	private const ERROR_MSG = 'Response IRIS error %s. Text: %s. Description: %s';

	/**
	 * @return Insolvency[]
	 */
	public static function hydrate(stdClass $resp): array
	{
		self::checkStatus($resp);

		if (!isset($resp->data)) {
			throw new ResponseException('Response does not contain "data" property');
		}

		$ins = [];

		if (is_array($resp->data)) {
			foreach ($resp->data as $i) {
				$ins[] = Insolvency::fromArray((array) $i);
			}
		} else {
			$ins[] = Insolvency::fromArray((array) $resp->data);
		}

		if (count($ins) === 0) {
			throw new NoRecordFoundException('No insolvency matching given params found.');
		}

		return $ins;
	}

	private static function checkStatus(stdClass $resp): void
	{
		if (!isset($resp->stav)) {
			throw new ResponseException('Response does not contain "stav" property');
		}

		if (isset($resp->stav->kodChyby)) {
			$code = $resp->stav->kodChyby;
			if ($code === self::ERROR_EMPTY_DATA) {
				throw new NoRecordFoundException('No insolvency matching given params found.');
			}

			if ($code === self::ERROR_INVALID_PARAMS_COMBINATION) {
				throw new InvalidParamsCombinationException(
					sprintf(
						self::ERROR_MSG,
						$resp->stav->kodChyby,
						$status['textChyby'] ?? 'Invalid params combination.',
						$status['popisChyby'] ?? 'Combination of request parameters is not valid.'
					)
				);
			}

			if ($code === self::ERROR_NAME_TOO_SHORT) {
				throw new InvalidParamsCombinationException(
					sprintf(
						self::ERROR_MSG,
						$resp->stav->kodChyby,
						$status['textChyby'] ?? 'Person name is too short',
						$status['popisChyby'] ?? 'nazevOsoby must contain at least 2 characters'
					)
				);
			}

			throw new ResponseException(
				sprintf(
					self::ERROR_MSG,
					$resp->stav->kodChyby,
					$status['textChyby'] ?? 'Unknown error',
					$status['popisChyby'] ?? ''
				)
			);
		}
	}

}
