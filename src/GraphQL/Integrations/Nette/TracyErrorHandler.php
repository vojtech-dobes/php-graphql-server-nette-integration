<?php declare(strict_types=1);

namespace Vojtechdobes\GraphQL\Integrations\Nette;

use Throwable;
use Tracy;
use Vojtechdobes\GraphQL;


final class TracyErrorHandler implements GraphQL\ErrorHandler
{

	public function handleFieldResolverError(
		Throwable $e,
		GraphQL\FieldSelection $fieldSelection,
	): void
	{
		Tracy\Debugger::log($e, Tracy\Debugger::EXCEPTION);
	}



	public function handleAbstractTypeResolverError(
		Throwable $e,
		GraphQL\FieldSelection $fieldSelection,
		mixed $objectValue,
	): void
	{
		Tracy\Debugger::log($e, Tracy\Debugger::EXCEPTION);
	}

}
