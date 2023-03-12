<?php
namespace Drupal\client_url\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\WidgetBase;

/**
 * Plugin implementation of the 'client_url_widget' widget.
 *
 * @FieldWidget(
 *   id = "client_url_widget",
 *   label = @Translation("Client URL Checkbox"),
 *   field_types = {
 *     "client_url"
 *   }
 * )
 */
class ClientUrlWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $file_path = drupal_get_path('module', 'client_url') . '/client_urls.txt';

    if (file_exists($file_path)) {
      $allowed_values = array_map('trim', file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
    } else {
      $allowed_values = [];
    }

    foreach ($allowed_values as $url) {
      $options[$url] = $this->urlToDomain($url);
    }

    $element['value'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Client URLs'),
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#options' => $options,
      '#description' => $this->t('Select one or more client URLs.'),
    ];

    return $element;
  }

  /**
   * Converts a URL to its domain name.
   *
   * @param string $url
   *   The URL to convert.
   *
   * @return string
   *   The domain name of the URL.
   */
  private function urlToDomain($url) {
    $url_parts = parse_url($url);
    if (isset($url_parts['host'])) {
      return $url_parts['host'];
    }
    return $url;
  }

}
