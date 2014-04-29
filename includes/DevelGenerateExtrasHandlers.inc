<?php

class DevelGenerateExtrasHandlers extends DevelGenerateExtrasBase {
  private $handlers;
  public function __construct($handlers, $info) {
    $this->handlers = $handlers;
    parent::__construct($info);
  }
  static function Create($bundle, $object) {
    $classes = module_invoke_all('devel_generate_extras_class', $bundle, $info);
    return new DevelGenerateExtrasHandlers($classes, $info);
  }
  public function GetHandlers() {
    return $this->handlers;
  }
  public function IsDummy() {
    $return = FALSE;
    foreach ($this->GetHandlers() as $handler) {
      if ($handler->IsDummy()) {
        return TRUE;
      }
    }
    return $return;
  }
  public function Populate() {
    $info = $this->GetInfo();
    foreach ($this->GetHandlers() as $handler) {
      if ($info) {
        $handler->SetInfo($info);
      }
      $handler->Populate();
      $info = $handler->GetInfo();
    }
    $this->SetInfo($info);
  }
}