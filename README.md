# A rate limiter middleware for Guzzle

[![Latest Version on Packagist](https://img.shields.io/packagist/v/asiadevmedia/guzzle-rate-limiter.svg?style=flat-square)](https://packagist.org/packages/asiadevmedia/guzzle-rate-limiter)
[![Quality Score](https://img.shields.io/scrutinizer/g/asiadevmedia/guzzle-rate-limiter.svg?style=flat-square)](https://scrutinizer-ci.com/g/asiadevmedia/guzzle-rate-limiter)
[![StyleCI](https://github.styleci.io/repos/165693657/shield?branch=master)](https://github.styleci.io/repos/165693657)
[![Total Downloads](https://img.shields.io/packagist/dt/asiadevmedia/guzzle-rate-limiter.svg?style=flat-square)](https://packagist.org/packages/asiadevmedia/guzzle-rate-limiter)

A rate limiter middleware for Guzzle. Here's what you need to know:

- Specify a maximum amount of requests per minute or per second
- When the limit is reached, the process will `sleep` until the request can be made
- Implement your own driver to persist the rate limiter's request store. This is necessary if the rate limiter needs to work across separate processes, the package ships with an `InMemoryStore`.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/guzzle-rate-limiter-middleware.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/guzzle-rate-limiter-middleware)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require asiadevmedia/guzzle-rate-limiter
```

## Usage

Create a [Guzzle middleware stack](http://docs.guzzlephp.org/en/stable/handlers-and-middleware.html) and register it on the client.

```php
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Asiadevmedia\GuzzleRateLimiter\RateLimiterMiddleware;

$stack = HandlerStack::create();
$stack->push(RateLimiterMiddleware::perSecond(3));

$client = new Client([
    'handler' => $stack,
]);
```

You can create a rate limiter to limit per second or per minute.

```php
RateLimiterMiddleware::perSecond(3); // Max. 3 requests per second

RateLimiterMiddleware::perMinute(5); // Max. 5 requests per minute
```

## Custom stores

By default, the rate limiter works in memory. This means that if you have a second PHP process (or Guzzle client) consuming the same API, you'd still possibly hit the rate limit. To work around this issue, the rate limiter's state should be persisted to a cache. Implement the `Store` interface with your own cache, and pass the store to the rate limiter.

```php
use MyApp\RateLimiterStore;
use Asiadevmedia\GuzzleRateLimiter\RateLimit;

RateLimiterMiddleware::perSecond(3, new RateLimiterStore());
```

A Laravel example of a custom `Store`:

```php
<?php

namespace MyApp;

use Asiadevmedia\GuzzleRateLimiter\Store;
use Illuminate\Support\Facades\Cache;

class RateLimiterStore implements Store
{
    public function get(): array
    {
        return Cache::get('rate-limiter', []);
    }

    public function push(int $timestamp, int $limit)
    {
        Cache::put('rate-limiter', array_merge($this->get(), [$timestamp]));
    }
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Kruikstraat 22, 2018 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
