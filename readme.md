# [PHP GraphQL Server](https://github.com/vojtech-dobes/php-graphql-server) integration for Nette Framework

![Checks](https://github.com/vojtech-dobes/php-graphql-server-nette-integration/actions/workflows/checks.yml/badge.svg?branch=master&event=push)

This integration provides:
- `Nette\DI\CompilerExtension` for comfortable configuration
- efficient loading of resolvers directly from `Nette\DI\Container`
- Tracy-compatible error handler



## Installation

To install the latest version, run the following command:

```
composer require vojtech-dobes/php-graphql-server-nette-integration
```

This package only works along [`vojtech-dobes/php-graphql-server`](https://github.com/vojtech-dobes/php-graphql-server). Make sure to install it explicitly as well.

Minimum supported PHP version is 8.4.



## Documentation

Start by registering the Nette DI extension in your configuration:

```neon
extensions:
  graphql: Vojtechdobes\GraphQL\Integrations\Nette\Extension
```

The minimum configuration can look like this:

```neon
graphql:
  schemaPath: %appDir%/schema.graphqls
  tempDir: %tempDir%/graphql

  fieldResolvers:
    Query.ping: App\PingFieldResolver
```

Here are possible fields you can specify:

| Field                      | Mandatory | Description |
|:---------------------------|:----------|:------------|
| `abstractTypeResolvers`    | no        | Map of abstract types to their respective resolvers (see [Resolving abstract types](https://github.com/vojtech-dobes/php-graphql-server/blob/master/docs/resolving-abstract-types.md)). |
| `autoReload`               | no        | By default `false`. Useful during development to automatically rebuild generated `Schema` factory class based on latest schema  & implementation changes. |
| `contextFactory`           | no        | By default `NullContextFactory`. Allows to specify `ContextFactory` service (see [Context](https://github.com/vojtech-dobes/php-graphql-server/blob/master/docs/context.md)).
| `enableIntrospection`      | no        | By default `false`. Determines whether introspection should be enabled by default. Can be overridden per request. |
| `enumClasses`              | no        | Map of enum types to PHP `BackedEnum` class (see [Native enum support](doc)). |
| `errorHandler`             | no        | By default `TracyErrorHandler`. Allows to specify `ErrorHandler` service (see [Handling runtime errors](https://github.com/vojtech-dobes/php-graphql-server/blob/master/docs/handling-runtime-errors.md)).
| `fieldResolvers`           | yes       | Map of individual fields in schema to their respective field resolvers (see [Resolving fields](https://github.com/vojtech-dobes/php-graphql-server/blob/master/docs/resolving-fields.md)). |
| `scalarImplementations`    | no        | Map of custom scalar types to their respective implementations (see [Custom scalars](https://github.com/vojtech-dobes/php-graphql-server/blob/master/docs/custom-scalars.md)). |
| `schemaPath`               | yes       | Path to your schema file. |
| `tempDir`                  | yes       | Path to temp directory where generated [`Schema`](https://github.com/vojtech-dobes/php-graphql-server/tree/master/src/GraphQL/TypeSystem/Schema.php) factory class will be stored. |
