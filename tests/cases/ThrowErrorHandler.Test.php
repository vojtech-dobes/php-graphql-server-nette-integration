<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';


$logger = new class implements Tracy\ILogger
{

	/** @var list<mixed> */
	public array $messages = [];

	public function log(mixed $value, string $level = self::INFO): void
	{
		$this->messages[] = $value instanceof Throwable
			? $value->getMessage()
			: $value;
	}

};

Tracy\Debugger::setLogger($logger);

/**
 * @implements Vojtechdobes\GraphQL\AbstractTypeResolver<mixed>
 */
final class IAbstractTypeResolver implements Vojtechdobes\GraphQL\AbstractTypeResolver
{

	/**
	 * @throws Exception
	 */
	public function resolveAbstractType(mixed $objectValue): string
	{
		throw new Exception("Something bad happened when resolving abstract type");
	}

}

$service = configureGraphQLService([
	'abstractTypeResolvers' => [
		'I' => IAbstractTypeResolver::class,
	],
	'fieldResolvers' => [
		'O.b' => new Nette\DI\Definitions\Statement(StaticFieldResolver::class, ['B']),
		'Query.a' => new Nette\DI\Definitions\Statement(FailFieldResolver::class, ['Something bad happened when resolving field']),
		'Query.i' => new Nette\DI\Definitions\Statement(StaticFieldResolver::class, [[]]),
	],
	'schemaPath' => __DIR__ . '/ThrowErrorHandler.Test.schema.graphqls',
]);


Tester\Assert::same(
	[
		'data' => [
			'a' => null,
			'i' => null,
		],
		'errors' => [
			[
				'message' => 'Field failed to resolve',
				'path' => ['a'],
			],
			[
				'message' => 'Abstract type I failed to resolve',
				'path' => ['i'],
			],
		],
	],
	$service->executeRequest(
		new Vojtechdobes\GraphQL\Request(
			$service->parseExecutableDocument('{ a i { b } }'),
			[],
		),
	)->wait()->toResponse(),
);

Tester\Assert::equal(
	[
		'Something bad happened when resolving field',
		'Something bad happened when resolving abstract type',
	],
	$logger->messages,
);
