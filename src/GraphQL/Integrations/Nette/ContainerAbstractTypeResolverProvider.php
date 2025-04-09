<?php declare(strict_types=1);

namespace Vojtechdobes\GraphQL\Integrations\Nette;

use LogicException;
use Nette;
use Vojtechdobes\GraphQL;


final class ContainerAbstractTypeResolverProvider implements GraphQL\AbstractTypeResolverProvider
{

	public function __construct(
		private readonly Nette\DI\Container $dic,
		private readonly string $extensionName,
	) {}



	public function hasAbstractTypeResolver(string $abstractTypeName): bool
	{
		return $this->dic->hasService("{$this->extensionName}.abstractType.{$abstractTypeName}");
	}



	public function getAbstractTypeResolver(string $abstractTypeName): GraphQL\AbstractTypeResolver
	{
		return $this->dic->getService("{$this->extensionName}.abstractType.{$abstractTypeName}"); // @phpstan-ignore return.type
	}



	public function listSupportedTypeNames(): array
	{
		throw new LogicException(
			self::class . " can't be used when validating " . GraphQL\ExecutableSchema::class,
		);
	}

}
