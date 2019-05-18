<?php

namespace Drupal\Tests\layout_library\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\node\Traits\ContentTypeCreationTrait;

/**
 * Tests using a layout from the library on an entity.
 *
 * @group layout_library
 */
class LayoutLibraryTest extends BrowserTestBase {

  use ContentTypeCreationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['layout_library', 'block', 'node', 'options'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalPlaceBlock('local_actions_block');
    // @todo The Layout Builder UI relies on local tasks; fix in
    //   https://www.drupal.org/project/drupal/issues/2917777.
    $this->drupalPlaceBlock('local_tasks_block');

    $this->createContentType([
      'type' => 'dogs',
    ]);
    $this->drupalLogin($this->drupalCreateUser([
      'configure any layout',
      'create dogs content',
      'edit own dogs content',
      'administer node display',
    ]));
  }

  /**
   * Tests using the layout.
   */
  public function testUseLayout() {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $node = $this->createNode(['type' => 'dogs']);

    // Add a layout to the library.
    $this->drupalGet('admin/structure/layouts');
    $this->clickLink('Add layout');
    $page->fillField('label', 'Slim Pug');
    $page->fillField('id', 'slim_pug');
    $page->selectFieldOption('_entity_type', 'node:dogs');
    $page->pressButton('Save');
    $assert_session->pageTextContains('Edit layout for Slim Pug');

    // Customize the library layout so we can tell it from the others.
    $page->clickLink('Add Block');
    $page->clickLink('Powered by Drupal');
    $page->fillField('settings[label]', 'This is from the library');
    $page->checkField('settings[label_display]');
    $page->pressButton('Add Block');
    // Add a field block to the layout to ensure that they can be rendered in
    // library layouts without causing infinite recursion.
    $page->clickLink('Add Block');
    $page->clickLink('Authored by');
    $page->pressButton('Add Block');
    $this->assertSession()->statusCodeEquals(200);
    $page->pressButton('Save layout');

    // Enable defaults and overrides for the display.
    $this->drupalGet('admin/structure/types/manage/dogs/display');
    $page->checkField('layout[enabled]');
    $page->checkField('layout[library]');
    $page->pressButton('Save');
    $page->checkField('layout[allow_custom]');
    $page->pressButton('Save');

    // Customize the default layout so we can tell it from the others.
    $page->clickLink('Manage layout');
    $page->clickLink('Add Section');
    $page->clickLink('One column');
    $page->clickLink('Add Block');
    $page->clickLink('Powered by Drupal');
    $page->fillField('settings[label]', 'This is from defaults');
    $page->checkField('settings[label_display]');
    $page->pressButton('Add Block');
    $page->pressButton('Save layout');

    $this->drupalGet($node->toUrl());
    $assert_session->pageTextContains('This is from defaults');
    $assert_session->pageTextNotContains('This is from the library');
    $assert_session->pageTextNotContains('This is from overrides');

    // Edit the layout to use the layout from the library.
    $page->clickLink('Edit');
    $page->selectFieldOption('Layout', 'slim_pug');
    $page->pressButton('Save');
    $assert_session->pageTextNotContains('This is from defaults');
    $assert_session->pageTextContains('This is from the library');
    $assert_session->pageTextNotContains('This is from overrides');

    // Customize the overridden layout so we can tell it from the others.
    $page->clickLink('Layout');
    $page->clickLink('Remove section');
    $page->pressButton('Remove');
    $page->clickLink('Add Section');
    $page->clickLink('One column');
    $page->clickLink('Add Block');
    $page->clickLink('Powered by Drupal');
    $page->fillField('settings[label]', 'This is from overrides');
    $page->checkField('settings[label_display]');
    $page->pressButton('Add Block');
    $page->pressButton('Save layout');
    $assert_session->pageTextNotContains('This is from defaults');
    $assert_session->pageTextNotContains('This is from the library');
    $assert_session->pageTextContains('This is from overrides');
  }

}
