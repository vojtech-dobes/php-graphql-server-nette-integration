<?php declare(strict_types=1);

/**
 * @implements Vojtechdobes\GraphQL\FieldResolver<null, never>
 */
final class FailFieldResolver implements Vojtechdobes\GraphQL\FieldResolver
{

	public function __construct(
		private readonly string $message,
	) {}



	public function resolveField(
		mixed $objectValue,
		Vojtechdobes\GraphQL\FieldSelection $field,
	): mixed
	{
		throw new Exception($this->message);
	}

}

/**
 * @implements Vojtechdobes\GraphQL\FieldResolver<null, mixed>
 */
final class StaticFieldResolver implements Vojtechdobes\GraphQL\FieldResolver
{

	public function __construct(
		private readonly mixed $value,
	) {}



	public function resolveField(
		mixed $objectValue,
		Vojtechdobes\GraphQL\FieldSelection $field,
	): mixed
	{
		return $this->value;
	}

}

/**
 * @param array<string, mixed> $config
 */
function configureGraphQLService(array $config): Vojtechdobes\GraphQL\Service
{
	$configurator = new Nette\Bootstrap\Configurator();
	$configurator->setDebugMode(true);
	$configurator->setTempDirectory(TempDir);
	$configurator->addConfig([
		'extensions' => [
			'graphql' => Vojtechdobes\GraphQL\Integrations\Nette\Extension::class,
		],
		'graphql' => array_replace([
			'autoReload' => true,
			'tempDir' => '%tempDir%/graphql',
		], $config),
	]);

	return $configurator
		->createContainer()
		->getByType(Vojtechdobes\GraphQL\Service::class);
}
