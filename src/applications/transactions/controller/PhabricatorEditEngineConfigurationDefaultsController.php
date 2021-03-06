<?php

final class PhabricatorEditEngineConfigurationDefaultsController
  extends PhabricatorEditEngineController {

  public function handleRequest(AphrontRequest $request) {
    $engine_key = $request->getURIData('engineKey');
    $this->setEngineKey($engine_key);

    $key = $request->getURIData('key');
    $viewer = $this->getViewer();

    $config = id(new PhabricatorEditEngineConfigurationQuery())
      ->setViewer($viewer)
      ->withEngineKeys(array($engine_key))
      ->withIdentifiers(array($key))
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$config) {
      return id(new Aphront404Response());
    }

    $cancel_uri = "/transactions/editengine/{$engine_key}/view/{$key}/";

    $engine = $config->getEngine();
    $fields = $engine->getFieldsForConfig($config);

    foreach ($fields as $key => $field) {
      if (!$field->getIsDefaultable()) {
        unset($fields[$key]);
        continue;
      }
    }

    foreach ($fields as $field) {
      $field->setIsEditDefaults(true);
    }

    if ($request->isFormPost()) {
      $xactions = array();

      foreach ($fields as $field) {
        $field->readValueFromSubmit($request);
      }

      $type = PhabricatorEditEngineConfigurationTransaction::TYPE_DEFAULT;

      $xactions = array();
      foreach ($fields as $field) {
        $new_value = $field->getValueForDefaults();
        $xactions[] = id(new PhabricatorEditEngineConfigurationTransaction())
          ->setTransactionType($type)
          ->setMetadataValue('field.key', $field->getKey())
          ->setNewValue($new_value);
      }

      $editor = id(new PhabricatorEditEngineConfigurationEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnMissingFields(true)
        ->setContinueOnNoEffect(true);

      $editor->applyTransactions($config, $xactions);

      return id(new AphrontRedirectResponse())
        ->setURI($cancel_uri);
    }

    $title = pht('Edit Form Defaults');

    $form = id(new AphrontFormView())
      ->setUser($viewer);

    foreach ($fields as $field) {
      $field->appendToForm($form);
    }

    $form
      ->appendControl(
        id(new AphrontFormSubmitControl())
          ->setValue(pht('Save Defaults'))
          ->addCancelButton($cancel_uri));

    $info = id(new PHUIInfoView())
      ->setSeverity(PHUIInfoView::SEVERITY_NOTICE)
      ->setErrors(
        array(
          pht('You are editing the default values for this form.'),
        ));


    $box = id(new PHUIObjectBoxView())
      ->setHeaderText($title)
      ->setInfoView($info)
      ->setForm($form);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Form %d', $config->getID()), $cancel_uri);
    $crumbs->addTextCrumb(pht('Edit Defaults'));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->appendChild($box);
  }

}
