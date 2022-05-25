<?php
    require 'vendor/autoload.php';
    use SebastianBergmann\CodeCoverage\Filter;
    use SebastianBergmann\CodeCoverage\Driver\Selector;
    use SebastianBergmann\CodeCoverage\CodeCoverage;
    use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReport;
    $coverages = glob("coverages/*.php");

    #increase the memory in multiples of 128M in case of memory error
    ini_set('memory_limit', '12800M');

    $filter->includeDirectory("/var/www/html");
    $final_coverage = new CodeCoverage(
        (new Selector)->forLineCoverage($filter),
        $filter
    );
    $count = count($coverages);
    $i = 0;
    foreach ($coverages as $coverage_file)
    {
        $i++;
        echo "Processing coverage ($i/$count) from $coverage_file". PHP_EOL;
        require_once($coverage_file);
        $final_coverage->merge($coverage);
    }

    #add the directories where source code files exists
    $final_coverage->filter()->addDirectoryToWhitelist("/var/www/html/");
    
    echo "Generating final report..." . PHP_EOL;
    $report = new HtmlReport;
    $report->process($final_coverage,"reports");
    echo "Report generated succesfully". PHP_EOL;
?>
