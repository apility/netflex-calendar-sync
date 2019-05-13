<?php
namespace Netflex\CalendarSync;

use Model\Structure;
use GuzzleHttp\Client;

/**
 * Wrapper for the Google Calendar event api.
 */
class GoogleCalendar {
  public function __construct(string $calendarId, string $apiKey, array $arguments = []) {
    $this->calendarId = $calendarId;
    $this->apiKey = $apiKey;

    $this->arguments = $arguments;
    $this->arguments['key'] = $apiKey;
    $this->arguments['syncToken'] = null;
    $this->data = null;
  }


  public function __call($name, $arguments)
  {
    if(!$this->data) {
      $this->performCall();
    }
    return $this->data->{$name}(...$arguments);
  }

  public function get() {
    if(!($this->data)) {
      $this->performCall();
    }
    return $this->data;
  }

  private function performCall() {
    $arguments = array_filter($this->arguments, function($arg) {
      return !is_null($arg);
    });
    $arguments = array_map(function($key, $value) {
      if(is_string($value)) {
        return "$key=$value";
      } else {
        return "$key=" . json_encode($value);
      }
      
    }, array_keys($arguments), array_values($arguments));
    $arguments = implode("&", $arguments);

    $calendar = urlencode($this->calendarId);
    try {
      $data = json_decode((new Client)->get("https://www.googleapis.com/calendar/v3/calendars/$calendar/events?$arguments")->getBody());
      $this->nextPageToken = $data->nextPageToken ?? null;
      $this->data = collect($data->items);
      return $this->data;
    } catch (Exception $e) {
      throw $e;
    }
  }
}
