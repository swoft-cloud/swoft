<?php

$header = <<<'EOF'
This file is part of Swoft.
(c) Swoft <group@swoft.org>
For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return PhpCsFixer\Config::create()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('runtime')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
