<?php
$phpCovFile = __DIR__.'/app/.coverage/php/coverage.txt';
$phpPattern = '/^\s*Lines\s*:\s*\s(\d+.?\d+)%.*$/';

$jsCovFile = __DIR__.'/app/.coverage/js/coverage-summary.txt';
$jsPattern = '/^\s*Statements\s*:\s*\s(\d+.?\d+)%.*$/';

function getCoverage(string $pattern, string $phpCovFile) {
    $fn = fopen($phpCovFile,"r");
    while(! feof($fn))  {
        $result = fgets($fn);
        $matches = [];
        if (preg_match($pattern,$result, $matches)){
            return $matches[1];
        }
    }
    fclose($fn);
}

function buildThresholdColor(int $number): string
{
    switch ($number){
        case $number < 10:
            return 'red';
        case $number < 50:
            return 'orange';
        case $number < 90:
            return 'yellow';
        case $number < 100:
            return 'green';
        default:
            return 'success';
    }
}

function updateReadme(string $name, string $coverage) {
    $coverageNumber = (int)$coverage;
    $color = buildThresholdColor($coverageNumber);

    $fn = fopen("README.md","rw");
    $content = [];
    while(! feof($fn))  {
        $result = fgets($fn);

        $pattern = '/'.$name.'-(\d+)%25-([a-z]+)/';
        $replace = $name.'-'.$coverageNumber.'%25-'.$color;
        $result = preg_replace($pattern, $replace, $result);

        $content[] = $result;
    }
    fclose($fn);

    $updatedContent = implode('', $content);
    file_put_contents('README.md', $updatedContent);
}

$phpCoverage = getCoverage($phpPattern, $phpCovFile);
$jsCoverage =  getCoverage($jsPattern, $jsCovFile);

updateReadme('php--coverage', $phpCoverage);
updateReadme('js--coverage', $jsCoverage);


