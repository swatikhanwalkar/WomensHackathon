<?php

namespace Drupal\Tests\lightning_layout;

use Drupal\Tests\lightning_core\FixtureBase;

final class FixtureContext extends FixtureBase {

  /**
   * @BeforeScenario
   */
  public function setUp() {
    $this->installModule('lightning_roles');
  }

  /**
   * @AfterScenario
   */
  public function tearDown() {
    parent::tearDown();
  }

}
