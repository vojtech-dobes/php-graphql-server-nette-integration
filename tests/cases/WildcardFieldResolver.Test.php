<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';


$service = configureGraphQLService([
	'fieldResolvers' => [
		'Query' => new Nette\DI\Definitions\Statement(StaticFieldResolver::class, ['Dude']),
	],
	'schemaPath' => __DIR__ . '/WildcardFieldResolver.Test.schema.graphqls',
]);


Tester\Assert::same(
	[
		'data' => [
			'a' => 'Dude',
		],
	],
	$service->executeRequest(
		new Vojtechdobes\GraphQL\Request(
			$service->parseExecutableDocument('{ a }'),
			null,
			[],
		),
	)->wait()->toResponse(),
);
