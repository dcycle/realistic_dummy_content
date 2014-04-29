<?php

abstract class DevelGenerateExtrasEntity extends DevelGenerateExtrasBase {
  abstract function GetBundle();
  function IsDummy() {
    $bundle = $this->GetBundle();
    foreach (module_invoke_all('devel_generate_extras_dummy', $bundle, $this->GetInfo()) as $dummy) {
      if ($dummy) {
        return TRUE;
      }
    }
    return FALSE;
  }
  function Populate() {
    return $this->_Populate_();
  }
  abstract function _Populate_();
}
