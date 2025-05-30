<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';


$service = configureGraphQLService([
	'enableIntrospection' => true,
	'fieldResolvers' => [
		'Query.a' => new Nette\DI\Definitions\Statement(StaticFieldResolver::class, ['A']),
	],
	'schemaPath' => __DIR__ . '/Introspection.Test.schema.graphqls',
]);


Tester\Assert::same(
	[
		'data' => [
			'__type' => [
				'fields' => [
					[
						'name' => 'a',
					],
				],
			],
		],
	],
	$service->executeRequest(
		new Vojtechdobes\GraphQL\Request(
			$service->parseExecutableDocument('{ __type(name: "Query") { fields { name } } }'),
			null,
			[],
		),
	)->wait()->toResponse(),
);
