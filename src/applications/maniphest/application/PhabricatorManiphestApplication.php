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
      ),
    );
  }

  public function loadStatus(PhabricatorUser $user) {
    $status = array();

    if (!$user->isLoggedIn()) {
      return $status;
    }

    $query = id(new ManiphestTaskQuery())
      ->setViewer($user)
      ->withStatuses(ManiphestTaskStatus::getOpenStatusConstants())
      ->withOwners(array($user->getPHID()))
      ->setLimit(self::MAX_STATUS_ITEMS);
    $count = count($query->execute());
    $count_str = self::formatStatusCount(
      $count,
      '%s Assigned Tasks',
      '%d Assigned Task(s)');

    $type = PhabricatorApplicationStatusView::TYPE_WARNING;
    $status[] = id(new PhabricatorApplicationStatusView())
      ->setType($type)
      ->setText($count_str)
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
                 ->setHref($this->getBaseURI().'task/create/?title=「知乎%20Web%20Bug」&projects=PHID-PROJ-ng2ucirw6embkz22vruj%2CPHID-PROJ-j2nwt65tpusqhhlsdgyd&assign=sheng&priority=50&description=NOTE%3A%20请参照%20%5B%5Bindex_qa/regulation/about_bugs/%20%7C%20Bug%20相关%5D%5D%20来填写%20task%20描述%0A%0A%3D%20Info%0A%0A%3E%20系统版本：%0A%3E%20操作系统：%0A%3E%20用户主页链接：%0A%3E%20出现频率：%0A%0A%3D%20重现步骤%0A%0A%3E%20具体的页面和操作%0A%0A%3D%20期望的结果%0A%0A%3D%20实际的结果%0A%0A%3D%20附加信息（log，截屏，其它信息）'),
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
                 ->setName(pht('提交设计需求'))
                 ->setAppIcon('maniphest-dark')
                 ->setHref($this->getBaseURI().'task/create/?title=%E8%AE%BE%E8%AE%A1%E9%9C%80%E6%B1%82-%EF%BC%88%E5%85%B7%E4%BD%93%E9%A1%B9%E7%9B%AE%EF%BC%89&projects=mkt&assign=ran&priority=50&description=%20%20-%20%2A%2A%E8%AE%BE%E8%AE%A1%E7%94%B3%E8%AF%B7%20-%20%E8%AE%BE%E8%AE%A1%E7%B1%BB%E5%9E%8B%20-%20%E4%B8%BB%E9%A2%98%2A%2A%0A%0A%0A%20%20-%20%2A%2A%E8%AE%BE%E8%AE%A1%E7%B1%BB%E5%9E%8B%EF%BC%9A%2A%2A%0A%0A//%EF%BC%88%E7%94%B5%E5%AD%90%E4%B9%A6%E5%B0%81%E9%9D%A2%20/%20%E7%AB%99%E5%86%85%20banner%20/%20app%20%E5%BC%80%E5%B1%8F%20/%20%E7%AB%99%E5%A4%96%E7%BA%BF%E4%B8%8A%E5%B9%BF%E5%91%8A%20/%20%E7%AB%99%E5%A4%96%E7%BA%BF%E4%B8%8B%E5%B9%BF%E5%91%8A%E7%AD%89%EF%BC%89//%0A%20%20-%20%2A%2A%E5%B0%BA%E5%AF%B8%E5%92%8C%E8%A7%84%E6%A0%BC%EF%BC%9A%2A%2A%0A%20%20-%20%2A%2A%E6%8F%90%E4%BA%A4%E6%97%A5%E6%9C%9F%EF%BC%9A%2A%2A%0A%20%20-%20%2A%2A%E7%A1%AE%E5%AE%9A%E5%90%8E%E7%9A%84%E6%96%87%E6%A1%88%EF%BC%9A%2A%2A%0A//%EF%BC%88%E7%94%B3%E8%AF%B7%E6%97%B6%E9%A1%BB%E6%9C%89%E7%A1%AE%E5%AE%9A%E7%9A%84%E6%96%87%E6%A1%88%E5%88%9B%E6%84%8F%EF%BC%8C%E5%90%8E%E6%9C%9F%E4%BF%AE%E6%94%B9%E5%B0%BD%E9%87%8F%E4%B8%8D%E8%A6%81%E5%81%8F%E7%A6%BB%E6%97%A2%E5%AE%9A%E7%9A%84%E6%96%87%E6%A1%88%E9%A3%8E%E6%A0%BC%EF%BC%89//%0A%20%20-%20%2A%2A%E9%A1%B9%E7%9B%AE%E6%A6%82%E5%86%B5%EF%BC%9A%2A%2A%0A%0A//%EF%BC%88%E4%BE%8B%E5%A6%82%E5%91%A8%E5%88%8A%E5%86%85%E5%AE%B9%E7%AE%80%E4%BB%8B%EF%BC%8C%E5%9C%86%E6%A1%8C%E5%86%85%E5%AE%B9%E7%AE%80%E4%BB%8B%EF%BC%8C%E4%B8%8A%E5%88%8A%E5%AA%92%E4%BD%93%E7%AE%80%E4%BB%8B%EF%BC%8C%E5%B8%AE%E5%8A%A9%E8%AE%BE%E8%AE%A1%E5%B8%88%E6%9B%B4%E5%A5%BD%E7%9A%84%E7%90%86%E8%A7%A3%E8%AE%BE%E8%AE%A1%E9%9C%80%E6%B1%82%EF%BC%89//%0A%20%20-%20%2A%2A%E5%8F%82%E8%80%83%E9%A3%8E%E6%A0%BC%EF%BC%9A%2A%2A%0A%0A//%EF%BC%88%E5%8F%82%E8%80%83%E5%9B%BE%E4%B8%BA%E4%BD%B3%EF%BC%8C%E6%AD%A4%E9%A1%B9%E5%8F%AF%E9%80%89%EF%BC%89//%0A%0A%0A%0A%2A%2A%2A%E4%B8%8A%E8%AF%89%E7%94%B3%E8%AF%B7%E8%AF%B7%E8%87%B3%E5%B0%91%E6%8F%90%E5%89%8D%E4%BA%94%E4%B8%AA%E5%B7%A5%E4%BD%9C%E6%97%A5%E6%8F%90%E4%BA%A4%2A%2A'),
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
