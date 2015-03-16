<?php

/**
 * @file
 *
 * Define RealisticDummyContentException autoload class.
 */

// When returning the caller of the function which resulted in the exception
// we need to go 4 levels deep. When returning the called function, we also
// need to 4 levels deep, but call GetCaller() through another function which adds
// a level (GetCalled()).
define('REALISTIC_DUMMY_CONTENT_EXCEPTION_BACKTRACE_LEVEL', 4);

/**
 * An Exception.
 */

class RealisticDummyContentException extends Exception {

  function __construct($message) {
    parent::__construct($message);
    $this->Log();
  }

  function Log() {
    debug($this->getMessage() . ' (' . $this->GetCaller() . ' called ' . $this->GetCalled() . ')');
  }

 /**
  * Returns the calling function through a backtrace
  */
  static function GetCaller() {
    // a funciton x has called a function y which called this
    // see stackoverflow.com/questions/190421
    $caller = debug_backtrace();
    $caller = $caller[REALISTIC_DUMMY_CONTENT_EXCEPTION_BACKTRACE_LEVEL];
    $r = $caller['function'] . '()';
    if (isset($caller['class'])) {
      $r .= ' in ' . $caller['class'];
    }
    if (isset($caller['object'])) {
      $r .= ' (' . get_class($caller['object']) . ')';
    }
    return $r;
  }

 /**
  * Returns the called function through a backtrace
  */
  static function GetCalled() {
    // Get caller will return the called function because the simple fact
    // of using another function will make the backtrace one-level deeper
    return self::GetCaller();
  }

}
