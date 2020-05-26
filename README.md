# php-calendar

A small lightweight library to handle with calendar operations.

This library is an API to extend native PHP DateTime features.

## Requirements
PHP >= 5.*

## Usage

```php
// Returns a DateTime instance.
var_dump(Calendar::today());
var_dump(Calendar::tomorrow());
var_dump(Calendar::yesterday());

// Get the next day of week based in current date
var_dump(Calendar::now()->nextMonday());
var_dump(Calendar::now()->nextWednesday());

// Returns an array of DateTime objects
$onlyFridays = Calendar::interval('2020-01-01', '2020-03-31')
                        ->onlyFridays();

$onlyTuesdays = Calendar::interval('2020-01-01', '2020-03-31')
                        ->onlyTuesdays();

var_dump($onlyFridays, $onlyTuesdays);

// You can also compute the next/before days from now
Calendar::now()->nextDays(15);
Calendar::now()->beforeDays(15);

// Or specifying a base date
Calendar::fromDate('2020-04-25')->nextDays(15);
Calendar::fromDate('2020-04-25')->beforeDays(15);

// Or even set a specific timezone before handle its operations
Calendar::setTimezone('America/Sao_Paulo');
```
