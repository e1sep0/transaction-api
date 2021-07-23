<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

$config = new PhpCsFixer\Config();

return $config->setRules(
    [
        '@PhpCsFixer' => true,
        '@PHP74Migration' => true,
        '@Symfony' => true,
    ]
)
    ->setFinder($finder);
