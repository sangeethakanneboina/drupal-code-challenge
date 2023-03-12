<?php

namespace Drupal\client_url\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a custom field type for client URLs.
 *
 * @FieldType(
 *   id = "client_url",
 *   label = @Translation("Client URL"),
 *   category = @Translation("General"),
 *   default_widget = "client_url_widget",
 *   default_formatter = "client_url_formatter",
 *   list_class = "\Drupal\Core\Field\EntityReferenceFieldItemList",
 * )
 */
class ClientUrl extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'locked' => TRUE, // Lock the field so it can't be removed
      'allowed_values' => static::getAllowedValues(),
    ] + parent::defaultStorageSettings();
  }

  /**
 * Gets the allowed values for this field.
 *
 * @return array
 *   An array of allowed values.
 */
public static function getAllowedValues() {
  $allowed_values = [];
  $module_path = \Drupal::moduleHandler()->getModule('client_url')->getPath();
  $file_path = $module_path . '/client_urls.txt';

  if (file_exists($file_path) && is_readable($file_path)) {
    $urls = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   
    foreach ($urls as $url) {
      $key = str_replace('.', '', $url);   

      $allowed_values[$key] = $url;
    }
  }
  return $allowed_values;
}/*
public static function getAllowedValues() {
  $file = drupal_get_path('module', 'client_url') . '/client_urls.txt';  
  $urls = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  return array_combine($urls, $urls);
}
*/
  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];

    $properties['url'] = DataDefinition::create('string')
      ->setLabel(t('URL'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [];

    $schema['columns']['url'] = [
      'type' => 'varchar',
      'length' => 255,
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('url')->getValue();
    return $value === NULL || $value === '';
  }

}
