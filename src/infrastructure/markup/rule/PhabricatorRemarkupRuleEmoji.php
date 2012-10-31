<?php

/**
 * @group markup
 */
final class PhabricatorRemarkupRuleEmoji
  extends PhutilRemarkupRule {

  const REGEX = '/:([a-z0-9\+\-_]+):/';

  public function apply($text) {
    return preg_replace_callback(
      self::REGEX,
      array($this, 'markupEmoji'),
      $text);
  }

  private function markupEmoji($matches) {
    $name = $matches[1];
    if ($this->hasEmoji($name)) {
      $img = phutil_render_tag(
        'img',
        array(
          'alt'    => $name,
          'width'  => '20',
          'height' => '20',
          'style'  => 'vertical-align:middle;',
          'src'    => '/rsrc/image/emoji/'.$name.'.png',
        ),
        '');
      return $this->getEngine()->storeText($img);
    } else {
      return $name;
    }
  }

  private function hasEmoji($name) {
    $files = Filesystem::listDirectory('rsrc/image/emoji', $include_hidden = false);
    foreach ($files as $file) {
      $info = pathinfo($file);
      $file_name = basename($file, '.'.$info['extension']);
      if ($file_name == $name) {
        return true;
      }
    }
    return false;
  }

}
