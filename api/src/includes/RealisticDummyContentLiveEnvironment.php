<?php

namespace Drupal\realistic_dummy_content_api\includes;

/**
 * The live environment.
 *
 * During normal execution, we want to do things like interact with the file-
 * system and such. However during testing we want to abstract that away. This
 * class represents the live environment.
 */
class RealisticDummyContentLiveEnvironment extends RealisticDummyContentEnvironment {

  /**
   * {@inheritdoc}
   */
  public function implementFileGetContents($filename) : string {
    $return = file_get_contents($filename);
    if ($return === FALSE) {
      throw new \Exception('Cannot get contents of ' . $filename);
    }
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function implementFileSaveData($data, $destination = NULL) {
    return file_save_data($data, $destination);
  }

}
