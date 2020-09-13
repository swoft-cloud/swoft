<?php

$header = <<<'EOF'
This file is part of Swoft.

@link     https://swoft.org
@document https://swoft.org/docs
@contact  group@swoft.org
@license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'class_attributes_separation' => true,
        'declare_strict_types' => true,
        'encoding' => true, // MUST use only UTF-8 without BOM
        'global_namespace_import' => [
          'import_constants' => true,
          'import_functions' => true,
        ],
        'header_comment' => [
          'comment_type' => 'PHPDoc',
          'header'       => $header,
          'separate'     => 'bottom'
        ],
        'no_unused_imports' => true,
        'single_quote' => true,
        'standardize_not_equals' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('resource')
            ->exclude('config')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
