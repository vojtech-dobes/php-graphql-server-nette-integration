<?php declare(strict_types=1);

namespace Vojtechdobes\GraphQL\Integrations\Nette;

use BackedEnum;
use Vojtechdobes\GraphQL;


final class ExtensionConfig
{

	/** @var array<string, mixed> */
	public array $abstractTypeResolvers = [];

	public bool $autoReload = false;

	public mixed $contextFactory = GraphQL\NullContextFactory::class;

	public bool $enableIntrospection = false;

	/** @var array<string, class-string<BackedEnum>> */
	public array $enumClasses = [];

	public mixed $errorHandler = TracyErrorHandler::class;

	/** @var array<string, mixed> */
	public array $fieldResolvers;

	/** @var array<string, mixed> */
	public array $scalarImplementations = [];

	public string $schemaPath;

	public string $tempDir;

}
