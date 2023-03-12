<?php
namespace Drupal\client_url\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'client_url_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "client_url_formatter",
 *   label = @Translation("Client URL Domain"),
 *   field_types = {
 *     "client_url"
 *   }
 * )
 */
class ClientUrlFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $domain = $this->urlToDomain($item->value);
      $elements[$delta] = [
        '#markup' => $domain,
      ];
    }

    return $elements;
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
