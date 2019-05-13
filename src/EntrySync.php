<?php
namespace Netflex\CalendarSync;
use Netflex\Structure;

class EntrySync {
  public function __construct($entry, $key = "calendarData") {
    
    if(!is_subclass_of($entry, Structure::class))
      throw new \InvalidArgumentException("First argument has to be a netflex structure object");
    $this->entry = $entry;
    $this->key = $key;
  }

  public function syncData(\JsonSerializable $data) {
    
    $this->entry->update([
      $this->key => json_encode($data),
    ]);
    $this->entry->save();
  }
}
?>