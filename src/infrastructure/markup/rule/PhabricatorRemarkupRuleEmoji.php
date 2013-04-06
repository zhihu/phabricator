<?php

/*
 * Copyright 2012 Zhihu
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
      $img = phutil_tag(
        'img',
        array(
          'alt'    => $name,
          'width'  => '20',
          'height' => '20',
          'style'  => 'vertical-align:middle;display:inline',
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
