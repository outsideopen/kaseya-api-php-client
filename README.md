
# Kaseya REST API Client Library for PHP

The Kaseya REST API Client Library enables you to work with Kaseya's REST API.

These client libraries are not officially supported by Kaseya.

## Requirements
* [PHP 5.5.0 or higher](http://www.php.net/)

## Installation

You can use **Composer**

### Composer

The preferred method is via [composer](https://getcomposer.org). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require outsideopen/kaseya-api-client
```

## Examples

### Basic Example

```php
// include your composer dependencies
require_once 'vendor/autoload.php';

$client = new Kaseya\Client("kaseya.example.com", "agent", "agent-password");
$service = new Kaseya\Service\Asset($client);
$results = $service->agents->all();
foreach ($results as $item) {
  echo $item['AgentId'], "<br /> \n";
}
```

## How Do I Contribute? ##

Please see the [contributing](CONTRIBUTING.md) page for more information. In particular, we love pull requests.