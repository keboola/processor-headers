# processor-headers

[![Build Status](https://travis-ci.org/keboola/processor-headers.svg?branch=master)](https://travis-ci.org/keboola/processor-headers)

Headers processor. Takes all CSV files in `/data/in/tables` (except `.manifest` files), autogenerates missing headers and stores the files to `/data/out/tables`. Ignores directory structure and deletes all non-csv files.
 
## Development
 
Clone this repository and init the workspace with following commands:

- `docker-compose build`

Then load some CSV files into `./data/in/tables`, create empty folder `./data/out/tables` and run 

- `docker-compose run --rm processor-headers`
 
# Integration
 - Build is started after push on [Travis CI](https://travis-ci.org/keboola/processor-headers)
 - [Build steps](https://github.com/keboola/processor-headers/blob/master/.travis.yml)
   - build image
   - execute tests against new image
   - publish image to ECR if release is tagged
   
# Usage

## Sample configuration

```
{  
    "definition": {
        "component": "keboola.processor.headers"
    }
}
```