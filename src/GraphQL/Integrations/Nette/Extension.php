<?php declare(strict_types=1);

namespace Vojtechdobes\GraphQL\Integrations\Nette;

use Nette;
use Vojtechdobes\GraphQL;


final class Extension extends Nette\DI\CompilerExtension
{

	public function getConfigSchema(): Nette\Schema\Schema
	{
		return Nette\Schema\Expect::from(new ExtensionConfig());
	}



	public function loadConfiguration(): void
	{
		$containerBuilder = $this->getContainerBuilder();

		/** @var ExtensionConfig $config */
		$config = $this->getConfig();

		$containerBuilder
			->addDefinition($this->prefix('schemaLoader'))
			->setFactory(GraphQL\SchemaLoader::class, [
				'autoReload' => $config->autoReload,
				'tempDir' => $config->tempDir,
			]);

		$containerBuilder
			->addDefinition($this->prefix('schema'))
			->setFactory($this->prefix('@schemaLoader::loadSchema'), [
				'backendValidator' => [
					new Nette\DI\Definitions\Reference($containerBuilder::ThisContainer),
					$this->getGraphQLBackendValidationMethodName(),
				],
				'enumClasses' => $config->enumClasses,
				'scalarImplementations' => $config->scalarImplementations,
				'schemaPath' => $config->schemaPath,
			]);

		foreach ($config->abstractTypeResolvers as $abstractTypeName => $abstractTypeResolverService) {
			$containerBuilder
				->addDefinition($this->prefix('abstractType.' . $abstractTypeName))
				->setFactory($abstractTypeResolverService)
				->setAutowired(false);
		}

		$containerBuilder
			->addDefinition($this->prefix('abstractTypeResolverProvider'))
			->setFactory(ContainerAbstractTypeResolverProvider::class, [
				'extensionName' => $this->name,
			])
			->setAutowired(false);

		foreach ($config->fieldResolvers as $fieldName => $fieldResolverService) {
			$containerBuilder
				->addDefinition($this->prefix('field.' . $fieldName))
				->setFactory($fieldResolverService)
				->setAutowired(false);
		}

		$containerBuilder
			->addDefinition($this->prefix('fieldResolverProvider'))
			->setFactory(ContainerFieldResolverProvider::class, [
				'extensionName' => $this->name,
			])
			->setAutowired(false);

		$containerBuilder
			->addDefinition($this->prefix('contextFactory'))
			->setFactory($config->contextFactory)
			->setAutowired(false);

		$containerBuilder
			->addDefinition($this->prefix('errorHandler'))
			->setFactory($config->errorHandler)
			->setAutowired(false);

		$containerBuilder
			->addDefinition($this->prefix('executableSchema'))
			->setFactory(GraphQL\ExecutableSchema::class, [
				'abstractTypeResolverProvider' => $this->prefix('@abstractTypeResolverProvider'),
				'contextFactory' => $this->prefix('@contextFactory'),
				'enableIntrospection' => $config->enableIntrospection,
				'errorHandler' => $this->prefix('@errorHandler'),
				'fieldResolverProvider' => $this->prefix('@fieldResolverProvider'),
				'schema' => $this->prefix('@schema'),
			]);

		$containerBuilder
			->addDefinition($this->prefix('service'))
			->setFactory(GraphQL\ServiceFactory::class . '::createService', [
				'executableSchema' => $this->prefix('@executableSchema'),
			]);
	}



	public function afterCompile(Nette\PhpGenerator\ClassType $class): void
	{
		/** @var ExtensionConfig $config */
		$config = $this->getConfig();

		$abstractTypeNamesWithResolver = array_keys($config->abstractTypeResolvers);
		$fieldNamesWithResolver = array_keys($config->fieldResolvers);

		$class
			->addMethod($this->getGraphQLBackendValidationMethodName())
			->setPublic()
			->setBody('return ?;', [
				[
					'abstractTypeNamesWithResolver' => $abstractTypeNamesWithResolver,
					'fieldNamesWithResolver' => $fieldNamesWithResolver,
				],
			]);
	}



	private function getGraphQLBackendValidationMethodName(): string
	{
		return 'getGraphQLBackendValidation_' . $this->name;
	}

}
