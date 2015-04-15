<?php

final class PhabricatorTypeaheadResult {

  private $name;
  private $uri;
  private $phid;
  private $priorityString;
  private $displayName;
  private $displayType;
  private $imageURI;
  private $priorityType;
  private $imageSprite;
  private $icon;
  private $closed;

  public function setIcon($icon) {
    $this->icon = $icon;
    return $this;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function setURI($uri) {
    $this->uri = $uri;
    return $this;
  }

  public function setPHID($phid) {
    $this->phid = $phid;
    return $this;
  }

  public function setPriorityString($priority_string) {
    $this->priorityString = $priority_string;
    return $this;
  }

  public function setDisplayName($display_name) {
    $this->displayName = $display_name;
    return $this;
  }

  public function setDisplayType($display_type) {
    $this->displayType = $display_type;
    return $this;
  }

  public function setImageURI($image_uri) {
    $this->imageURI = $image_uri;
    return $this;
  }

  public function setPriorityType($priority_type) {
    $this->priorityType = $priority_type;
    return $this;
  }

  public function setImageSprite($image_sprite) {
    $this->imageSprite = $image_sprite;
    return $this;
  }

  public function setClosed($closed) {
    $this->closed = $closed;
    return $this;
  }

  public function getName() {
    return $this->name;
  }

  public function getDisplayName() {
    return coalesce($this->displayName, $this->getName());
  }

  public function getIcon() {
    return nonempty($this->icon, $this->getDefaultIcon());
  }

  public function getPHID() {
    return $this->phid;
  }

  public function getWireFormat() {
    $data = array(
      $this->name,
      $this->uri ? (string)$this->uri : null,
      $this->phid,
      $this->priorityString,
      $this->displayName,
      $this->displayType,
      $this->imageURI ? (string)$this->imageURI : null,
      $this->priorityType,
      $this->getIcon(),
      $this->closed,
      $this->imageSprite ? (string)$this->imageSprite : null,
    );
    while (end($data) === null) {
      array_pop($data);
    }
    return $data;
  }

  /**
   * If the datasource did not specify an icon explicitly, try to select a
   * default based on PHID type.
   */
  private function getDefaultIcon() {
    static $icon_map;
    if ($icon_map === null) {
      $types = PhabricatorPHIDType::getAllTypes();

      $map = array();
      foreach ($types as $type) {
        $icon = $type->getTypeIcon();
        if ($icon !== null) {
          $map[$type->getTypeConstant()] = "{$icon} bluegrey";
        }
      }

      $icon_map = $map;
    }

    $phid_type = phid_get_type($this->phid);
    if (isset($icon_map[$phid_type])) {
      return $icon_map[$phid_type];
    }

    return null;
  }

}
