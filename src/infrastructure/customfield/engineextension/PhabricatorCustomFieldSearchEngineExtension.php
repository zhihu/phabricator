<?php

final class PhabricatorCustomFieldSearchEngineExtension
  extends PhabricatorSearchEngineExtension {

  const EXTENSIONKEY = 'customfield';

  public function isExtensionEnabled() {
    return true;
  }

  public function getExtensionName() {
    return pht('Support for Custom Fields');
  }

  public function supportsObject($object) {
    return ($object instanceof PhabricatorCustomFieldInterface);
  }

  public function getExtensionOrder() {
    return 9000;
  }

  public function getSearchFields($object) {
    $engine = $this->getSearchEngine();
    $custom_fields = $this->getCustomFields($object);

    $fields = array();
    foreach ($custom_fields as $field) {
      $fields[] = id(new PhabricatorSearchCustomFieldProxyField())
        ->setSearchEngine($engine)
        ->setCustomField($field);
    }

    return $fields;
  }

  public function applyConstraintsToQuery(
    $object,
    $query,
    PhabricatorSavedQuery $saved,
    array $map) {

    $engine = $this->getSearchEngine();
    $fields = $this->getCustomFields($object);

    foreach ($fields as $field) {
      $field->applyApplicationSearchConstraintToQuery(
        $engine,
        $query,
        $saved->getParameter('custom:'.$field->getFieldIndex()));
    }
  }

  private function getCustomFields($object) {
    $fields = PhabricatorCustomField::getObjectFields(
      $object,
      PhabricatorCustomField::ROLE_APPLICATIONSEARCH);
    $fields->setViewer($this->getViewer());

    return $fields->getFields();
  }

  public function getFieldSpecificationsForConduit($object) {
    $fields = PhabricatorCustomField::getObjectFields(
      $object,
      PhabricatorCustomField::ROLE_CONDUIT);

    $map = array();
    foreach ($fields->getFields() as $field) {
      $key = $field->getModernFieldKey();

      // TODO: These should have proper types.
      $map[] = id(new PhabricatorConduitSearchFieldSpecification())
        ->setKey($key)
        ->setType('wild')
        ->setDescription($field->getFieldDescription());
    }

    return $map;
  }

  public function getFieldValuesForConduit($object) {
    // TODO: This is currently very inefficient. We should bulk-load these
    // field values instead.

    $fields = PhabricatorCustomField::getObjectFields(
      $object,
      PhabricatorCustomField::ROLE_CONDUIT);

    $fields
      ->setViewer($this->getViewer())
      ->readFieldsFromStorage($object);

    $map = array();
    foreach ($fields->getFields() as $field) {
      $key = $field->getModernFieldKey();
      $map[$key] = $field->getConduitDictionaryValue();
    }

    return $map;
  }

}
