<?php

final class DifferentialNewDiffMail extends DifferentialReviewRequestMail {

  protected function renderVaryPrefix() {
    $revision = $this->getRevision();
    $line_count = $revision->getLineCount();
    $lines = pht('%d line(s)', $line_count);

    if ($this->isFirstMailToRecipients()) {
      $verb = 'Request';
    } else {
      $verb = 'Updated';
    }

    return "[{$verb}, {$lines}]";
  }

  protected function renderBody() {
    $actor = $this->getActorName();

    $name  = $this->getRevision()->getTitle();

    $body = array();

    if ($this->isFirstMailToRecipients()) {
      $body[] = pht('%s requested code review of "%s".', $actor, $name);
    } else {
      $body[] = pht('%s updated the revision "%s".', $actor, $name);
    }
    $body[] = null;

    $body[] = $this->renderReviewRequestBody();

    return implode("\n", $body);
  }
}
