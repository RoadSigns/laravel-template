<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $parameters = $ecsConfig->parameters();
    $services = $ecsConfig->services();

    $services->set(NoUnusedImportsFixer::class);

    $ecsConfig->import(SetList::PSR_12);

    $parameters->set(Option::SKIP, [
        BinaryOperatorSpacesFixer::class => '~',
        VisibilityRequiredFixer::class => './tests/phpspec',
    ]);

    $parameters->set(Option::SKIP, [
        __DIR__ . '/app/Transport/Console/Kernel.php',
    ]);
};
