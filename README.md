#php-calendar

TODO: library description.

##Usage

```
// Returns a DateTime instance.
var_dump(Calendar::today());
var_dump(Calendar::tomorrow());
var_dump(Calendar::yesterday());

// Returns an array of DateTime objects
$onlyFridays = Calendar::interval('2020-01-01', '2020-03-31')
                        ->onlyFridays();

$onlyTuesdays = Calendar::interval('2020-01-01', '2020-03-31')
                        ->onlyTuesdays();

var_dump($onlyFridays);
var_dump($onlyTuesdays);

// Get the next day of week based in a given date.
$nextMonday = Calendar::interval('2020-01-01')->nextMonday();
$nextSunday = Calendar::interval('2020-01-01')->nextSunday();

var_dump($nextMonday);
var_dump($nextSunday);
```
