<?php
declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector;
use Rector\TypeDeclaration\Rector\Function_\AddFunctionVoidReturnTypeWhereNoReturnRector;

$cacheDir = getenv('RECTOR_CACHE_DIR') ?: sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'rector';

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])

    ->withCache(
        cacheClass: FileCacheStorage::class,
        cacheDirectory: $cacheDir,
    )

    ->withPhpSets()
    ->withAttributesSets()

    ->withSets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::INSTANCEOF,
        SetList::TYPE_DECLARATION,
    ])

    ->withSkip([
        ClassPropertyAssignToConstructorPromotionRector::class,
        CatchExceptionNameMatchingTypeRector::class,
        ClosureToArrowFunctionRector::class,
        RemoveUselessReturnTagRector::class,
        ReturnTypeFromStrictFluentReturnRector::class,
        NewlineAfterStatementRector::class,
        StringClassNameToClassConstantRector::class,
        ReturnTypeFromStrictTypedCallRector::class,
        ParamTypeByMethodCallTypeRector::class,
        AddFunctionVoidReturnTypeWhereNoReturnRector::class,
//        StringableForToStringRector::class,
//        CompactToVariablesRector::class,
//        SplitDoubleAssignRector::class,
//        ChangeOrIfContinueToMultiContinueRector::class,
//        ExplicitBoolCompareRector::class,
//        NewlineBeforeNewAssignSetRector::class,
//        SimplifyEmptyCheckOnEmptyArrayRector::class,
//        DisallowedEmptyRuleFixerRector::class,
    ]);
