![CI](https://github.com/renoki-co/reddit-json-api/workflows/CI/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/renoki-co/reddit-json-api/branch/master/graph/badge.svg)](https://codecov.io/gh/renoki-co/reddit-json-api/branch/master)
[![StyleCI](https://github.styleci.io/repos/166289783/shield?branch=master)](https://github.styleci.io/repos/166289783)
[![Latest Stable Version](https://poser.pugx.org/rennokki/reddit-json-api/v/stable)](https://packagist.org/packages/rennokki/reddit-json-api)
[![Total Downloads](https://poser.pugx.org/rennokki/reddit-json-api/downloads)](https://packagist.org/packages/rennokki/reddit-json-api)
[![Monthly Downloads](https://poser.pugx.org/rennokki/reddit-json-api/d/monthly)](https://packagist.org/packages/rennokki/reddit-json-api)
[![License](https://poser.pugx.org/rennokki/reddit-json-api/license)](https://packagist.org/packages/rennokki/reddit-json-api)

## 🚀 Installation

You can install the package via composer:

```bash
composer require rennokki/reddit-json-api
```

## 🙌 Usage

```php
use Rennokki\RedditApi\Reddit;

$app = Reddit::app(
    'renoki-co/reddit-json-api',
    '2.0',
    'web',
    'someusername'
);

$subreddit = Reddit::subreddit(
    'funny', // subreddit name
    $app
);

$posts = $subreddit->get()['data']['children'] ?? [];
```

## Sorting

Reddit allows sorting by posts type. The currently used ones are:

```php
public static $sorts = [
    'hot', 'new', 'controversial', 'top', 'rising',
];
```

To apply the sorting, you should call `sort()`:

```php
$subreddit = Reddit::subreddit('funny', $app);

$subreddit->sort('top');
```

## Time Filtering

Same as sorting, time filters are only a few:

```php
public static $times = [
    'hour', 'day', 'week', 'month', 'year', 'all',
];
```

```php
$subreddit = Reddit::subreddit('funny', $app);

// Top, all time sorting.
$subreddit
    ->sort('top')
    ->time('all');
```

## Limit

By default, each call gives you `20` posts.

```php
$subreddit = Reddit::subreddit('funny', $app);

$subreddit->setLimit(100);
```

## Before & After tokens

For paginating, you shall specify before & after from previous requests:

```php
$subreddit = Reddit::subreddit('funny', $app);

$subreddit->before(...);

$subreddit->after(...);
```

## Debugging

If you wish to inspect the URL that is being called, you ca do so:

```php
$subreddit = Reddit::subreddit('funny', $app);

$subreddit
    ->setLimit(30)
    ->sort('top')
    ->time('week');

$url = $subreddit->getCallableUrl();
```

## 🐛 Testing

``` bash
vendor/bin/phpunit
```

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 🔒  Security

If you discover any security related issues, please email alex@renoki.org instead of using the issue tracker.

## 🎉 Credits

- [Alex Renoki](https://github.com/rennokki)
- [All Contributors](../../contributors)

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
