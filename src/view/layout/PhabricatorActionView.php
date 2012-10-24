<?php

/*
 * Copyright 2012 Facebook, Inc.
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

final class PhabricatorActionView extends AphrontView {

  private $name;
  private $user;
  private $icon;
  private $href;
  private $disabled;
  private $workflow;
  private $renderAsForm;

  public function setHref($href) {
    $this->href = $href;
    return $this;
  }

  public function setIcon($icon) {
    $this->icon = $icon;
    return $this;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function setDisabled($disabled) {
    $this->disabled = $disabled;
    return $this;
  }

  public function setWorkflow($workflow) {
    $this->workflow = $workflow;
    return $this;
  }

  public function setRenderAsForm($form) {
    $this->renderAsForm = $form;
    return $this;
  }

  public function setUser(PhabricatorUser $user) {
    $this->user = $user;
    return $this;
  }

  public function render() {

    $icon = null;
    if ($this->icon) {
      $icon = phutil_render_tag(
        'span',
        array(
          'class' => 'phabricator-action-view-icon autosprite '.
                       'action-'.$this->icon,
        ),
        '');
    }

    if ($this->href) {
      if ($this->renderAsForm) {
        if (!$this->user) {
          throw new Exception(
            'Call setUser() when rendering an action as a form.');
        }

        $item = javelin_render_tag(
          'button',
          array(
            'class' => 'phabricator-action-view-item',
          ),
          phutil_escape_html($this->name));

        $item = phabricator_render_form(
          $this->user,
          array(
            'action'    => $this->href,
            'method'    => 'POST',
            'sigil'     => $this->workflow ? 'workflow' : null,
          ),
          $item);
      } else {
        $item = javelin_render_tag(
          'a',
          array(
            'href'  => $this->href,
            'class' => 'phabricator-action-view-item',
            'sigil' => $this->workflow ? 'workflow' : null,
          ),
          phutil_escape_html($this->name));
      }
    } else {
      $item = phutil_render_tag(
        'span',
        array(
          'class' => 'phabricator-action-view-item',
        ),
        phutil_escape_html($this->name));
    }

    $classes = array();
    $classes[] = 'phabricator-action-view';
    if ($this->disabled) {
      $classes[] = 'phabricator-action-view-disabled';
    }

    return phutil_render_tag(
      'li',
      array(
        'class' => implode(' ', $classes),
      ),
      $icon.$item);
  }

  public static function getAvailableIcons() {
    return array(
      'delete',
      'edit',
      'file',
      'flag-0',
      'flag-1',
      'flag-2',
      'flag-3',
      'flag-4',
      'flag-5',
      'flag-6',
      'flag-7',
      'flag-ghost',
      'fork',
      'move',
      'new',
      'preview',
      'subscribe-add',
      'subscribe-auto',
      'subscribe-delete',
      'unpublish',
      'world',
    );
  }

}
