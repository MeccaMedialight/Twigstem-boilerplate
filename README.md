# Twigstem
Twigstem - Rapid prototyping with Twig templates

## Installation

CD into the project directory and run composer install

```
composer install
```
Install node modules

```
npm install
```
The final directory structure:

``` 
├── public
│   ├── .htaccess
│   ├── index.php  
├── src
│   ├── data
│   ├── views
├── vendor
├─- composer.json
```

## Overview

Twigstem will attempt to load a template matching the requested url.

```
/about => loads views/about.twig
/more/info =>  loads views/more/info.twig
```

Add new templates to the src/views directory.

## Data

Data can be associated with a page in any of these ways:
1. Add a json file with the same name as the page

```
views/products.twig
views/products.json
```


2. Add a json file with the same name as the page to the data folder.

```
views/products.twig
data/products.json
```

3. Add a comment to the page specifying the data source. Only json files in the data folder can be specified this way

```
{# data src: index.json #}
```
You can optionally include an ID when specifying a data file. If an ID is provided, the data returned is include in the page context under this ID.
For example

```
{# data src: products.json #}
{# data id:sizes src: sizelist.json #}
```

## Extending Twig

Twigstem will look for a class called TwigExtension. If found, this will
be instantiated and added to twig as an extension. 