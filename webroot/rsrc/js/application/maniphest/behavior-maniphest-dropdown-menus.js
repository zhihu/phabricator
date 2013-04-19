/**
 * @provides javelin-behavior-maniphest-dropdown-menus
 * @requires javelin-behavior
 *           javelin-dom
 *           javelin-util
 *           javelin-stratcom
 *           phabricator-dropdown-menu
 *           phabricator-menu-item
 */

JX.behavior('maniphest-dropdown-menus', function(config) {

  function link_to(name, uri) {
    return new JX.PhabricatorMenuItem(name, null, uri);
  }

  var icon = JX.DOM.scry(window.document, 'span', 'phabricator-maniphest-menu')[0];
  var menu = new JX.PhabricatorDropdownMenu(icon, 'maniphest-dropdown-menu-frame');
  menu.addItem(link_to('新建任务', '/maniphest/task/create'));
  menu.addItem(link_to('提交 Bug', '/maniphest/task/create/?projects=PHID-PROJ-ng2ucirw6embkz22vruj'));
  menu.addItem(link_to('提交新想法和建议', '/maniphest/task/create/?projects=PHID-PROJ-jjdydjiyr2275zl7qjvq'));
  menu.addItem(link_to('本周上线记录', '/w/index_team/zhihu_release_note'));

  JX.Stratcom.listen(
    'click',
    'phabricator-maniphest-menu',
    function(e) {
      e.prevent();
    });

});
