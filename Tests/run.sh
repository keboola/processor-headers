#!/bin/bash
set -e

echo "Starting tests" >&1

docker-compose run --rm -e "KBC_DATADIR=/test-data/data1/" processor-headers
if diff --brief --recursive ./tests/data1/out/tables/ ./tests/data1/out/sample-tables/ ; then
    printf "Case 1 successful.\n"
else
    printf "Case 1 failed.\n"
    diff --recursive ./tests/data1/out/tables/ ./tests/data1/out/sample-tables/
fi

docker-compose run --rm -e "KBC_DATADIR=/test-data/data2/" -e "KBC_PARAMETER_DELIMITER=	" -e "KBC_PARAMETER_ENCLOSURE='" processor-headers
if diff --brief --recursive ./tests/data2/out/tables/ ./tests/data2/out/sample-tables/ ; then
    printf "Case 2 successful.\n"
else
    printf "Case 2 failed.\n"
    diff --recursive ./tests/data2/out/tables/ ./tests/data2/out/sample-tables/
fi
