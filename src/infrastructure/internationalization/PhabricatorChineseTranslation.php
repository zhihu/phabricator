<?php

final class PhabricatorChineseTranslation
  extends PhabricatorTranslation {

  final public function getLanguage() {
    return 'en';
  }

  public function getName() {
    return '中文';
  }

  public function getTranslations() {
    return array(
      'Differential Revision(s)' => array(
        'Differential Revision',
        'Differential Revisions',
      ),
      'file(s)' => array('file', 'files'),
      'Maniphest Task(s)' => array('Maniphest Task', 'Maniphest Tasks'),

      'Please fix these errors and try again.' => array(
        'Please fix this error and try again.',
        'Please fix these errors and try again.',
      ),

      '%d Error(s)' => array('%d Error', '%d Errors'),
      '%d Warning(s)' => array('%d Warning', '%d Warnings'),
      '%d Auto-Fix(es)' => array('%d Auto-Fix', '%d Auto-Fixes'),
      '%d Advice(s)' => array('%d Advice', '%d Pieces of Advice'),
      '%d Detail(s)' => array('%d Detail', '%d Details'),

      '(%d line(s))' => array('(%d line)', '(%d lines)'),

      'COMMIT(S)' => array('COMMIT', 'COMMITS'),

      '%d line(s)' => array('%d line', '%d lines'),

      'added %d commit(s): %s' => array(
        'added commit: %2$s',
        'added commits: %2$s',
      ),

      'removed %d commit(s): %s' => array(
        'removed commit: %2$s',
        'removed commits: %2$s',
      ),

      'changed %d commit(s), added %d: %s; removed %d: %s' =>
        'changed commits, added: %3$s; removed: %5$s',

      'ATTACHED %d COMMIT(S)' => array(
        'ATTACHED COMMIT',
        'ATTACHED COMMITS',
      ),

      'added %d dependencie(s): %s' => array(
        'added dependency: %2$s',
        'added dependencies: %2$s',
      ),

      'added %d dependent task(s): %s' => array(
        'added dependent task: %2$s',
        'added dependent tasks: %2$s',
      ),

      'removed %d dependencie(s): %s' => array(
        'removed dependency: %2$s',
        'removed dependencies: %2$s',
      ),

      'removed %d dependent task(s): %s' => array(
        'removed dependent task: %2$s',
        'removed dependent tasks: %2$s',
      ),

      'changed %d dependencie(s), added %d: %s; removed %d: %s' =>
        'changed dependencies, added: %3$s; removed: %5$s',

      'changed %d dependent task(s), added %d: %s; removed %d: %s',
        'changed dependent tasks, added: %3$s; removed: %5$s',

      'DEPENDENT %d TASK(s)' => array(
        'DEPENDENT TASK',
        'DEPENDENT TASKS',
      ),

      'DEPENDS ON %d TASK(S)' => array(
        'DEPENDS ON TASK',
        'DEPENDS ON TASKS',
      ),

      'DIFFERENTIAL %d REVISION(S)' => array(
        'DIFFERENTIAL REVISION',
        'DIFFERENTIAL REVISIONS',
      ),

      'added %d revision(s): %s' => array(
        'added revision: %2$s',
        'added revisions: %2$s',
      ),

      'removed %d revision(s): %s' => array(
        'removed revision: %2$s',
        'removed revisions: %2$s',
      ),

      'changed %d revision(s), added %d: %s; removed %d: %s' =>
        'changed revisions, added %3$s; removed %5$s',

      'There are %d raw fact(s) in storage.' => array(
        'There is %d raw fact in storage.',
        'There are %d raw facts in storage.',
      ),

      'There are %d aggregate fact(s) in storage.' => array(
        'There is %d aggregate fact in storage.',
        'There are %d aggregate facts in storage.',
      ),

      '%d Commit(s) Awaiting Audit' => array(
        '%d Commit Awaiting Audit',
        '%d Commits Awaiting Audit',
      ),

      '%d Problem Commit(s)' => array(
        '%d Problem Commit',
        '%d Problem Commits',
      ),

      '%d Review(s) Need Attention' => array(
        '%d Review Needs Attention',
        '%d Reviews Need Attention',
      ),

      '%d Review(s) Waiting on Others' => array(
        '%d Review Waiting on Others',
        '%d Reviews Waiting on Others',
      ),

      '%d Flagged Object(s)' => array(
        '%d Flagged Object',
        '%d Flagged Objects',
      ),

      '%d Unbreak Now Task(s)!' => array(
        '%d Unbreak Now Task!',
        '%d Unbreak Now Tasks!',
      ),

      '%d Assigned Task(s)' => array(
        '%d Assigned Task',
        '%d Assigned Tasks',
      ),

      'AM' => '上午',
      'PM' => '下午',

      'Mon' => '星期一',
      'Tue' => '星期二',
      'Wed' => '星期三',
      'Thu' => '星期四',
      'Fri' => '星期五',
      'Sat' => '星期六',
      'Sun' => '星期日',

      'Jan' => '一月',
      'Feb' => '二月',
      'Mar' => '三月',
      'Apr' => '四月',
      'May' => '五月',
      'Jun' => '六月',
      'Jul' => '七月',
      'Aug' => '八月',
      'Sep' => '九月',
      'Oct' => '十月',
      'Nov' => '十一月',
      'Dec' => '十二月',

      'Today' => '今天',
      'Yesterday' => '昨天',
    );
  }

}

