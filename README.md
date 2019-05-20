# Netflex Calendar Sync
A simple library for performing a one way sync from a provider to netflex structures.

## Installation
Include the repository from Composer.

```
  $ composer require apility/netflex-calendar-sync
```

## Calendars
### Google Calendar
In order to scrape a calendar, Create an Api Key at the google cloud platform [Here](https://console.cloud.google.com/).
This is a generic API key and its not Google Calendar specific. Enable access to the Google Calendar API through the same console.

Open your calendar app, ([Google Calendar](https://calendar.google.com)) and enter settings section for that calendar.

![](https://raw.githubusercontent.com/apility/netflex-calendar-sync/master/docs/google_cal_settings.png)

Find the ID of the calendar

![](https://raw.githubusercontent.com/apility/netflex-calendar-sync/master/docs/google_cal_id.png) and make the calendar public.

Create a calendar instance like this; 
```php
/*
  First option is the calendar ID.
  Second option is the API key, 
  Third option is an associative array of key values found in the Google API documentation
*/
$calendar = new GoogleCalendar($openingHourEntry->calendarId, \get_setting("google_calendar_sync_api_key"), [
  'singleEvents' => true,
]);
```

[Google API Explorer/documentation](https://developers.google.com/apis-explorer/#p/calendar/v3/calendar.events.list)

The items in the calendar is accessable as a Laravel Collection.

```php
$openingHours = $calendar
  ->map(function($event) {
    return [
      "eventId" => $event->id,
      "name" => "Åpningstid",
      "start" => Carbon::parse($event->start->dateTime)->format(DateTime::ISO8601),
      "end" => Carbon::parse($event->end->dateTime)->format(DateTime::ISO8601),
      "allDay" => false,
    ];
  });

```

## Resolvers
Resolvers are ways to immediately transfer a resolved object into Netflex. 

### EntrySync Resolver
The entry resolver takes any `Netflex\Structure` object and lets you inject any JSON serializeable data into
it. (For example a Laravel Collection).

```php
$resolver = new EntrySync(Models\Calendar::find(10000));
$resolver->syncData($openingHours);
```

