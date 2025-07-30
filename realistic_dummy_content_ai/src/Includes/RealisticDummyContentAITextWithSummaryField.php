<?php

namespace Drupal\realistic_dummy_content_ai\Includes;

use Drupal\field\Entity\FieldConfig;
use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentTextWithSummaryField;
use Drupal\ai\OperationType\Chat\ChatInput;
use Drupal\ai\OperationType\Chat\ChatMessage;
use Drupal\realistic_dummy_content_api\Framework\Framework;

/**
 * AI-powered field modifier for text_with_summary fields.
 *
 * Generates realistic dummy content using AI based on field configuration.
 */
class RealisticDummyContentAITextWithSummaryField extends RealisticDummyContentTextWithSummaryField {

  /**
   * Gets the field configuration for the specified field.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $bundle
   *   The bundle name.
   * @param string $field_name
   *   The field name.
   *
   * @return \Drupal\field\Entity\FieldConfig|null
   *   The field configuration object or NULL if not found.
   */
  protected function getFieldConfig(string $entity_type_id, string $bundle, string $field_name): ?object {
    return FieldConfig::loadByName($entity_type_id, $bundle, $field_name);
  }

  /**
   * Gets the help text from field configuration.
   *
   * @param \Drupal\field\Entity\FieldConfig|null $field_config
   *   The field configuration object.
   *
   * @return string
   *   The field help text or empty string if not available.
   */
  protected function getFieldHelpText(?object $field_config): string {
    return $field_config ? $field_config->getDescription() : '';
  }

  /**
   * Generates the user prompt for AI text generation.
   *
   * @param string $help_text
   *   The field help text.
   * @param string $content_type
   *   The content type machine name.
   *
   * @return string
   *   The generated prompt.
   */
  protected function getUserPrompt(string $help_text, string $content_type): string {
    return $help_text ?: 'write a random number paragraph on ' . $content_type;
  }

  /**
   * Generates the system prompt for AI text generation.
   *
   * @param string $content_type
   *   The content type label.
   *
   * @return string
   *   The system prompt.
   */
  protected function getSystemPrompt(string $content_type): string {
    return 'You are a helpful assistant.';
  }

  /**
   * Cleans the AI-generated text by removing the first non-empty line.
   *
   * @param string $text
   *   The raw AI-generated text.
   *
   * @return string
   *   The cleaned text.
   */
  protected function cleanGeneratedText(string $text): string {
    $lines = explode("\n", $text);
    $clean_lines = [];
    $skipped = FALSE;

    foreach ($lines as $line) {
      $trimmed = trim($line);
      if (!$skipped && $trimmed !== '') {
        $skipped = TRUE;
        continue;
      }
      if ($skipped) {
        $clean_lines[] = $line;
      }
    }

    return implode("\n", $clean_lines);
  }

  /**
   * {@inheritdoc}
   */
  public function change(): void {
    $entity = $this->getEntity()->getEntity();
    $entity_type_id = $entity->getEntityTypeId();
    $bundle = $entity->bundle();
    $field_name = $this->getName();

    $field_config = $this->getFieldConfig($entity_type_id, $bundle, $field_name);
    $content_type_label = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->load($bundle)
      ->label();

    $messages = new ChatInput([
      new ChatMessage('system', $this->getSystemPrompt($content_type_label)),
      new ChatMessage('user', $this->getUserPrompt(
        $this->getFieldHelpText($field_config),
        $bundle
      )),
    ]);

    try {
      $sets = \Drupal::service('ai.provider')->getDefaultProviderForOperationType('chat');
      $provider = \Drupal::service('ai.provider')->createInstance($sets['provider_id']);
      $response = $provider->chat($messages, $sets['model_id'])->getNormalized();

      if ($text = $response->getText()) {
        $cleaned_text = $this->cleanGeneratedText($text);
        Framework::instance()->setEntityProperty($entity, $field_name, $cleaned_text);
        $this->getEntity()->setEntity($entity);
      }
    }
    catch (\Exception $e) {
      \Drupal::logger('ai_text')->error('AI text generation failed: @message', ['@message' => $e->getMessage()]);
      \Drupal::messenger()->addError('Failed to generate AI text.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementValueFromFile($file): array {
    return [];
  }

}
