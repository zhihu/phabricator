<?php

final class PhabricatorManiphestApplication extends PhabricatorApplication {

  public function getName() {
    return pht('Maniphest');
  }

  public function getShortDescription() {
    return pht('Tasks and Bugs');
  }

  public function getBaseURI() {
    return '/maniphest/';
  }

  public function getIconName() {
    return 'maniphest';
  }

  public function isPinnedByDefault(PhabricatorUser $viewer) {
    return true;
  }

  public function getApplicationOrder() {
    return 0.110;
  }

  public function getFactObjectsForAnalysis() {
    return array(
      new ManiphestTask(),
    );
  }

  public function getEventListeners() {
    return array(
      new ManiphestNameIndexEventListener(),
      new ManiphestActionMenuEventListener(),
      new ManiphestHovercardEventListener(),
    );
  }

  public function getRemarkupRules() {
    return array(
      new ManiphestRemarkupRule(),
    );
  }

  public function getRoutes() {
    return array(
      '/T(?P<id>[1-9]\d*)' => 'ManiphestTaskDetailController',
      '/maniphest/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?' => 'ManiphestTaskListController',
        'report/(?:(?P<view>\w+)/)?' => 'ManiphestReportController',
        'batch/' => 'ManiphestBatchEditController',
        'task/' => array(
          'create/' => 'ManiphestTaskEditController',
          'edit/(?P<id>[1-9]\d*)/' => 'ManiphestTaskEditController',
          'descriptionpreview/'
            => 'PhabricatorMarkupPreviewController',
        ),
        'transaction/' => array(
          'save/' => 'ManiphestTransactionSaveController',
          'preview/(?P<id>[1-9]\d*)/'
            => 'ManiphestTransactionPreviewController',
        ),
        'export/(?P<key>[^/]+)/' => 'ManiphestExportController',
        'subpriority/' => 'ManiphestSubpriorityController',
        'subscribe/(?P<action>add|rem)/(?P<id>[1-9]\d*)/'
          => 'ManiphestSubscribeController',
      ),
    );
  }

  public function loadStatus(PhabricatorUser $user) {
    $status = array();

    $query = id(new ManiphestTaskQuery())
      ->setViewer($user)
      ->withStatuses(ManiphestTaskStatus::getOpenStatusConstants())
      ->withOwners(array($user->getPHID()));
    $count = count($query->execute());

    $type = PhabricatorApplicationStatusView::TYPE_WARNING;
    $status[] = id(new PhabricatorApplicationStatusView())
      ->setType($type)
      ->setText(pht('%s Assigned Task(s)', new PhutilNumber($count)))
      ->setCount($count);

    return $status;
  }

  public function getQuickCreateItems(PhabricatorUser $viewer) {
    $items = array();

    $item = id(new PHUIListItemView())
      ->setName(pht('Maniphest Task'))
      ->setIcon('fa-anchor')
      ->setHref($this->getBaseURI().'task/create/');
    $items[] = $item;

    # Add special Zhihu menu items
    array_push($items,
               id(new PHUIListItemView())
                 ->setName(pht('反馈 Web Bug 以及可用性问题'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?title=「知乎%20Web%20Bug」&projects=PHID-PROJ-ng2ucirw6embkz22vruj&assign=gcj&priority=50&description=NOTE%3A%20请参照%20%5B%5Bindex_qa/regulation/about_bugs/%20%7C%20Bug%20相关%5D%5D%20来填写%20task%20描述%0A%0A%3D%20Info%0A%0A%3E%20系统版本：%0A%3E%20操作系统：%0A%3E%20用户主页链接：%0A%3E%20出现频率：%0A%0A%3D%20重现步骤%0A%0A%3E%20具体的页面和操作%0A%0A%3D%20期望的结果%0A%0A%3D%20实际的结果%0A%0A%3D%20附加信息（log，截屏，其它信息）'),
               id(new PHUIListItemView())
                 ->setName(pht('反馈知乎 Android Bug'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?title=「知乎%20Android%20Bug」&projects=PHID-PROJ-xuykevhhyppli7r6okqo&assign=joosun&priority=50&description=NOTE%3A%20请参照%20%5B%5Bindex_qa/regulation/about_bugs/%20%7C%20Bug%20相关%5D%5D%20来填写%20task%20描述%0A%0A%3D%20Info%0A%0A%3E%20系统版本：%0A%3E%20操作系统：%0A%3E%20App%20的版本号：%0A%3E%20用户主页链接：%0A%3E%20出现频率：%0A%0A%3D%20重现步骤%0A%0A%3E%20具体的页面和操作%0A%0A%3D%20期望的结果%0A%0A%3D%20实际的结果%0A%0A%3D%20附加信息（log，截屏，其它信息）'),
               id(new PHUIListItemView())
                 ->setName(pht('反馈知乎 iOS Bug'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?title=「知乎%20iOS%20Bug」&projects=PHID-PROJ-7ol45igp2m3i6r3md5fe&assign=botao&priority=50&description=NOTE%3A%20请参照%20%5B%5Bindex_qa/regulation/about_bugs/%20%7C%20Bug%20相关%5D%5D%20来填写%20task%20描述%0A%0A%3D%20Info%0A%0A%3E%20系统版本：%0A%3E%20操作系统：%0A%3E%20App%20的版本号：%0A%3E%20用户主页链接：%0A%3E%20出现频率：%0A%0A%3D%20重现步骤%0A%0A%3E%20具体的页面和操作%0A%0A%3D%20期望的结果%0A%0A%3D%20实际的结果%0A%0A%3D%20附加信息（log，截屏，其它信息）'),
               id(new PHUIListItemView())
                 ->setName(pht('反馈日报 Android Bug'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?title=「日报%20Android%20Bug」&projects=PHID-PROJ-hnbu6cweou7akqmly2p7&assign=yu&priority=50&description=NOTE%3A%20 请参照%20%5B%5Bindex_qa/regulation/about_bugs/%20%7C%20Bug%20相关%5D%5D%20来填写%20task%20描述%0A%0A%3D%20Info%0A%0A%3E%20系统版本：%0A%3E%20操作系统：%0A%3E%20App%20的版本号：%0A%3E%20用户主页链接：%0A%3E%20出现频率：%0A%0A%3D%20重现步骤%0A%0A%3E%20具体的页面和操作%0A%0A%3D%20期望的结果%0A%0A%3D%20实际的结果%0A%0A%3D%20附加信息（log，截屏，其它信息）'),
               id(new PHUIListItemView())
                 ->setName(pht('反馈日报 iOS Bug'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?title=「日报%20iOS%20Bug」&projects=PHID-PROJ-hnbu6cweou7akqmly2p7&assign=sgl&priority=50&description=NOTE%3A%20 请参照%20%5B%5Bindex_qa/regulation/about_bugs/%20%7C%20Bug%20相关%5D%5D%20来填写%20task%20描述%0A%0A%3D%20Info%0A%0A%3E%20系统版本：%0A%3E%20操作系统：%0A%3E%20App%20的版本号：%0A%3E%20用户主页链接：%0A%3E%20出现频率：%0A%0A%3D%20重现步骤%0A%0A%3E%20具体的页面和操作%0A%0A%3D%20期望的结果%0A%0A%3D%20实际的结果%0A%0A%3D%20附加信息（log，截屏，其它信息）'),
               id(new PHUIListItemView())
                 ->setName(pht('Feature Request'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?projects=PHID-PROJ-jjdydjiyr2275zl7qjvq'),
               id(new PHUIListItemView())
                 ->setName(pht('Call SA'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?projects=PHID-PROJ-eipqemisx4qzrejiehbz&description=NOTE:%20%E8%AF%B7%E5%8F%82%E7%85%A7%20%5B%5Bdev/index_it_operations/callsa%20%7C%20%E8%BF%90%E7%BB%B4%E6%97%A5%E5%B8%B8%E9%9C%80%E6%B1%82%E6%B3%A8%E6%84%8F%E4%BA%8B%E9%A1%B9%5D%5D%20%E6%9D%A5%E5%A1%AB%E5%86%99%20task%20%E6%8F%8F%E8%BF%B0'));

    return $items;
  }

  protected function getCustomCapabilities() {
    return array(
      ManiphestDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for newly created tasks.'),
      ),
      ManiphestDefaultEditCapability::CAPABILITY => array(
        'caption' => pht('Default edit policy for newly created tasks.'),
      ),
      ManiphestEditStatusCapability::CAPABILITY => array(),
      ManiphestEditAssignCapability::CAPABILITY => array(),
      ManiphestEditPoliciesCapability::CAPABILITY => array(),
      ManiphestEditPriorityCapability::CAPABILITY => array(),
      ManiphestEditProjectsCapability::CAPABILITY => array(),
      ManiphestBulkEditCapability::CAPABILITY => array(),
    );
  }

}
