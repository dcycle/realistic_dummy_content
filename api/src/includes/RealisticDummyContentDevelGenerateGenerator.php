<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\Framework\Framework;

/**
 * The "devel generate" dummy content generator.
 */
class RealisticDummyContentDevelGenerateGenerator extends RealisticDummyContentGenerator {

  /**
   * {@inheritdoc}
   */
  public function implementGenerate() {
    $info['entity_type'] = $this->getType();
    $info['kill'] = $this->getKill();
    $info['num'] = $this->getNum();

    if (Framework::instance()->moduleExists('comment')) {
      $info['max_comments'] = 5;
    }

    if ($this->getType() == 'node') {
      // See https://www.drupal.org/node/2324027
      $info = array_merge($info, [
        'node_types' => [
          $this->getBundle() => $this->getBundle(),
        ],
        'authors' => [
          1,
        ],
        'title_length' => 3,
        "skip_fields" => [],
        "base_fields" => [],
        "add_type_label" => 0,
        "add_alias" => 0,
        "add_statistics" => TRUE,
      ]);
    }

    Framework::instance()->develGenerate($info);
  }

}
