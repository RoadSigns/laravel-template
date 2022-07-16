# Laravel Template

This is a basic Laravel template configured in a Domain Driven Design approach.

## Installation

_This requires Docker desktop to be installed on your device._

You can install the application using the command

```bash
make setup
```

## Contributing

When contributing to the application the CI with run the following command
```bash
make test
```
This will check to see if all the unit tests, coding standard, static analyse the code and run Behat tests.

These can be run manually as well via the following commands.

Run just the Unit tests
```bash
make phpunit
```

Run just the ECS checks
```bash
make cs-check
```
Run just the ECS checks adn fixes any easy issues
```bash
make cs-fix
```

Runs PHPStan to statically analyse the code
```bash
make phpstan
```

Runs the Behat tests
```bash
make behat
```