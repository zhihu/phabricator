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

abstract class PhabricatorDirectoryController extends PhabricatorController {

  public function buildStandardPageResponse($view, array $data) {
    $page = $this->buildStandardPageView();

    $page->setBaseURI('/');
    $page->setTitle(idx($data, 'title'));

    $page->setGlyph("\xE2\x9A\x92");
    $page->appendChild($view);

    $response = new AphrontWebpageResponse();
    return $response->setContent($page->render());
  }

  public function buildNav() {
    $user = $this->getRequest()->getUser();

    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI('/'));

    $nav->addLabel('知乎海盗船');
    $nav->addFilter('home', '常用功能', '/');
    $nav->addFilter('feed', '全局动态');
    $nav->addSpacer();
    $nav->addFilter('submit_bug', '提交ISSUE', '/maniphest/task/create/?projects=PHID-PROJ-ng2ucirw6embkz22vruj');
    $nav->addFilter('submit_idea', '提交想法', '/maniphest/task/create/?projects=PHID-PROJ-jjdydjiyr2275zl7qjvq');
    $nav->addFilter('submit_idea', '提交用户反馈', '/maniphest/task/create/?projects=PHID-PROJ-sj23435jqi7ugo3sltel');
    $nav->addFilter('projects', '项目列表', '/project/filter/allactive/');
    $nav->addFilter('blog', '博客列表', '/phame/post/all/');
    $nav->addSpacer();
    $nav->addFilter('applications', '更多功能');

    return $nav;
  }

}
