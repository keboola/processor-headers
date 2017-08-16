<?php

require('vendor/autoload.php');

try {
    $dataDir = getenv('KBC_DATADIR') === false ? '/data/' : getenv('KBC_DATADIR');
    $delimiter = getenv('KBC_PARAMETER_DELIMITER') === false ? ',' : getenv('KBC_PARAMETER_DELIMITER');
    $enclosure = getenv('KBC_PARAMETER_ENCLOSURE') === false ? '"' : getenv('KBC_PARAMETER_ENCLOSURE');
    $escapedBy = getenv('KBC_PARAMETER_ESCAPED_BY') === false ? '' : getenv('KBC_PARAMETER_ESCAPED_BY');
    $files = new FilesystemIterator($dataDir . 'in/tables/', FilesystemIterator::SKIP_DOTS);
    $destination = $dataDir . 'out/tables/';
    /** @var FilesystemIterator $file */
    foreach ($files as $file) {
        if ($file->getExtension() == 'manifest') {
            copy($file->getPathname(), $destination . $file->getFilename());
        } else {
            $sourceCsv = new \Keboola\Csv\CsvFile($file->getPathname(), $delimiter, $enclosure, $escapedBy);
            $maxColCount = 0;
            foreach ($sourceCsv as $row) {
                $maxColCount = max($maxColCount, count($row));
            }
            $header = $sourceCsv->getHeader();
            for ($i = 0; $i < $maxColCount; $i++) {
                if (empty($header[$i])) {
                    $header[$i] = 'auto_col_' . $i;
                }
            }
            $destinationCsv = new \Keboola\Csv\CsvFile($destination . $file->getFilename(), $delimiter, $enclosure, $escapedBy);
            $destinationCsv->writeRow($header);
            foreach ($sourceCsv as $index => $row) {
                if ($index > 0) {
                    // skip header
                    $destinationCsv->writeRow(array_pad($row, $maxColCount, ''));
                }
            }
        }
    }
} catch (\Keboola\Csv\InvalidArgumentException $e) {
    echo $e->getMessage();
    exit(1);
} catch (\Exception $e) {
    echo $e->getMessage();
    exit(2);
}
