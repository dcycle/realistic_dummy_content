<?php

declare(strict_types=1);

namespace Drupal\realistic_dummy_content_ai\Drush\Commands;

use Drupal\Core\File\FileSystemInterface;
use Drupal\ai\AiProviderPluginManager;
use Drupal\ai\OperationType\TextToImage\TextToImageInput;
use Drush\Attributes as CLI;
use Drush\Boot\DrupalBootLevels;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides Drush commands that integrate with the AI module.
 */
class RealisticDummyContentAiCommands extends DrushCommands {

  /**
   * Constructor.
   */
  public function __construct(
    protected FileSystemInterface $fileSystem,
    protected AiProviderPluginManager $aiProviderPluginManager,
  ) {
    parent::__construct();
  }

  /**
   * Create instance.
   */
  public static function create(ContainerInterface $container): RealisticDummyContentAiCommands {
    return new RealisticDummyContentAiCommands(
      $container->get('file_system'),
      $container->get('ai.provider')
    );
  }

  /**
   * Generate AI images based on prompt.
   */
  #[CLI\Command(name: 'rd_ai_images:generate-images')]
  #[CLI\Argument(name: 'module', description: 'The machine name of the module where images should be placed.')]
  #[CLI\Argument(name: 'relative_path', description: 'Path within the module where images should be stored.')]
  #[CLI\Argument(name: 'count', description: 'Number of images to generate.')]
  #[CLI\Argument(name: 'prompt', description: 'Prompt for image generation.')]
  #[CLI\Option(name: 'overwrite', description: 'If set, existing files in the directory will be overwritten.')]
  #[CLI\Option(name: 'size', description: 'Image size (e.g., 1024x1024).')]
  #[CLI\Help(
    description: 'Generates one or more AI-generated images using the given prompt.',
        synopsis: 'drush rd_ai_images:generate-images my_module realistic_dummy_content/fields/node/article/field_image 3 "A spaceship landing at night" --overwrite --size=512x512'
  )]
  #[CLI\Bootstrap(level: DrupalBootLevels::FULL)]
  public function generateImages(
    string $module,
    string $relative_path,
    int $count,
    string $prompt,
    array $options = ['overwrite' => FALSE, 'size' => '1024x1024']
  ): void {

    $overwrite = isset($options['overwrite']) && $options['overwrite'] !== FALSE;
    $size = $options['size'] ?? '1024x1024';

    // Resolve destination path.
    $module_path = \Drupal::service('extension.list.module')->getPath($module);
    $destination = "$module_path/$relative_path";

    // Ensure directory exists and is writable.
    $this->fileSystem->prepareDirectory(
      $destination,
      FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS
    );

    $extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $files = [];

    // Delete existing images if overwrite is enabled.
    if ((bool) $overwrite) {
      foreach ($extensions as $ext) {
        $files = array_merge($files, glob("$destination/*.$ext"));
      }

      if ($files) {
        foreach ($files as $file) {
          $this->fileSystem->unlink($file);
        }
      }
    }

    // Setup provider.
    $sets = $this->aiProviderPluginManager->getDefaultProviderForOperationType('text_to_image');
    $provider = $this->aiProviderPluginManager->createInstance($sets['provider_id']);
    $generatedFiles = [];

    for ($i = 0; $i < $count; $i++) {
      $seed = random_int(1, 2147483647);

      $provider->setConfiguration([
        'response_format' => 'url',
        'accept' => 'image/png',
        'image_size' => $size,
        'quality' => 'standard',
        'style' => 'vivid',
        'seed' => $seed,
        'cfg_scale' => random_int(7, 14),
        'sampler' => 'K_DPMPP_2M',
        'steps' => 30,
        'safety_check' => TRUE,
        'prompt_cache_max_len' => 0,
        'num_images' => 1,
      ]);

      // Append something minor to the prompt to help induce variation.
      $adjustedPrompt = $prompt . ' #' . uniqid();

      $input = new TextToImageInput($adjustedPrompt);
      $images = $provider->textToImage($input, $sets['model_id'])->getNormalized();

      if (empty($images)) {
        throw new \RuntimeException("AI provider returned no images.");
      }

      foreach ($images as $image) {
        $filename = $this->generateUniqueFilename($destination);
        $filepath = "$destination/$filename";

        $this->fileSystem->saveData(
          $image->getBinary(),
          $filepath,
          FileSystemInterface::EXISTS_REPLACE
        );

        $generatedFiles[] = $filepath;
        $this->output()->writeln("<info>Generated:</info> $filepath");
      }
    }

    $this->output()->writeln("<comment>Total images created:</comment> " . count($generatedFiles));
  }

  /**
   * Generate a unique filename in the given directory.
   */
  private function generateUniqueFilename(string $directory): string {
    $filename = 'ai_' . substr(md5(microtime() . random_int(0, 999999)), 0, 12) . '.png';
    $counter = 0;

    while (file_exists("$directory/$filename")) {
      $counter++;
      $filename = 'ai_' . substr(md5(microtime() . random_int(0, 999999) . $counter), 0, 12) . '.png';
    }

    return $filename;
  }

}
