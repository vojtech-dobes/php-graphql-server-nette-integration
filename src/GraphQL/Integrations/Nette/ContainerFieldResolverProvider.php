<?php declare(strict_types=1);

namespace Vojtechdobes\GraphQL\Integrations\Nette;

use LogicException;
use Nette;
use Vojtechdobes\GraphQL;


final class ContainerFieldResolverProvider implements GraphQL\FieldResolverProvider
{

	public function __construct(
		private readonly Nette\DI\Container $dic,
		private readonly string $extensionName,
	) {}



	public function hasFieldResolver(string $fieldName): bool
	{
		return $this->dic->hasService("{$this->extensionName}.field.{$fieldName}");
	}



	/**
	 * @return GraphQL\FieldResolver<mixed, mixed, array<string, mixed>>
	 */
	public function getFieldResolver(string $fieldName): ?GraphQL\FieldResolver
	{
		try {
			return $this->dic->getService("{$this->extensionName}.field.{$fieldName}"); // @phpstan-ignore return.type
		} catch (Nette\DI\MissingServiceException) {
			return null;
		}
	}



	public function getFieldResolverClass(string $fieldName): ?string
	{
		try {
			return $this->dic->getServiceType("{$this->extensionName}.field.{$fieldName}"); // @phpstan-ignore return.type
		} catch (Nette\DI\MissingServiceException) {
			return null;
		}
	}



	public function listSupportedFieldNames(): never
	{
		throw new LogicException(
			self::class . " can't be used when validating " . GraphQL\ExecutableSchema::class,
		);
	}

}
