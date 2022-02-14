<?php

$finder = (new PhpCsFixer\Finder)
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests/src')
    ->ignoreVCSIgnored(true);

return (new \DistortedFusion\PhpCsFixerConfig\Config)->setFinder($finder);
