# Netflex Calendar Sync
A simple library for performing a one way sync from a provider to netflex structures.

## Installation
Include the repository from Composer.

```
  $ composer require apility/netflex-calendar-sync
```

## Sync methods.
By default, the library will resovle recurring events to separate events.
### Model Sync
The Model sync will sync a calendar to a structure. Define a Netflex\Structure, tell it how to resolve an event to an entry,

```php

use Netflex\CalendarSync\GoogleCalendar;
use Netflex\CalendarSync\ModelResolver;

$calendar = new GoogleCalendar(...$argsGoHere);

// eventId is the key on the OpeningHour class where the calendar event id is stored
(new ModelResolver(Models\OpeningHours::class, "eventId"))
  ->resolveEvent(function($event) {
    return [
      'eventId' => $event->id
    ];
  })
  ->sync($calendar);

```
### Legacy Sync
Sync an entire calendar into a single entry.

```php
use Netflex\CalendarSync\GoogleCalendar;
use Netflex\CalendarSync\LegacyResolver;

$calendar = new GoogleCalendar(...$argsGoHere);
(new LegacyResolver(Models\\CalendarData::find(10000), "dataField"))
  ->sync($calendar);
```