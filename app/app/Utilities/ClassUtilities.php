<?php
namespace App\Utilities;

class ClassUtilities
{
    public static function extractModelFromRepositoryName(string $repositoryName): string
    {
        $pattern = '/^App.*\\\(.*)Repository$/';
        $replace = 'App\\\Models\\\$1';
        return preg_replace($pattern, $replace, $repositoryName);
    }
}
