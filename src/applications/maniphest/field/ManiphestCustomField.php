<?php

abstract class ManiphestCustomField
  extends PhabricatorCustomField {

  public function newStorageObject() {
    return new ManiphestCustomFieldStorage();
  }

  protected function newStringIndexStorage() {
    return new ManiphestCustomFieldStringIndex();
  }

  protected function newNumericIndexStorage() {
    return new ManiphestCustomFieldNumericIndex();
  }

  /**
   * When the user creates a task, the UI prompts them to "Create another
   * similar task". This copies some fields (e.g., Owner and CCs) but not other
   * fields (e.g., description). If this custom field should also be copied,
   * return true from this method.
   *
   * @return bool True to copy the default value from the template task when
   *              creating a new similar task.
   */
  public function shouldCopyWhenCreatingSimilarTask() {
    return false;
  }


  // TODO: All of this is legacy junk.

  public function renderTransactionEmailVerb(
    ManiphestTransaction $transaction) {
    return null;
  }

  public function renderTransactionDescription(
    ManiphestTransaction $transaction) {
    $old = $transaction->getOldValue();
    $new = $transaction->getNewValue();
    return pht(
      'updated field %s from %s to %s',
      $this->getFieldName(),
      hsprintf('%s', $old),
      hsprintf('%s', $new));
  }

}
