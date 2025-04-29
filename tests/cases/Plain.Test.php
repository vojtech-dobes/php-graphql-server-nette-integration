<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';


$service = configureGraphQLService([
	'fieldResolvers' => [
		'Query.a' => new Nette\DI\Definitions\Statement(StaticFieldResolver::class, ['A']),
	],
	'schemaPath' => __DIR__ . '/Plain.Test.schema.graphqls',
]);


Tester\Assert::same(
	[
		'data' => [
			'a' => 'A',
		],
	],
	$service->executeRequest(
		new Vojtechdobes\GraphQL\Request(
			$service->parseExecutableDocument('{ a }'),
			[],
		),
	)->wait()->toResponse(),
);
