<?php

abstract class DevelGenerateExtrasBase {
  private $info;
  public function __construct($info) {
    $this->SetInfo($info);
  }
  public function SetInfo($info) {
    $this->info = $info;
  }
  function GetInfo() {
    return $this->info;
  }
  abstract function IsDummy();
  static function Handlers($bundle, $info) {
    $return = array();
    switch ($bundle) {
      case 'node':
        $return[] = new DevelGenerateExtrasNode($info);
        break;
      case 'user':
        $return[] = new DevelGenerateExtrasUser($info);
        break;
      default:
        break;
    }
    return $return;
  }
  abstract function Populate();
}
