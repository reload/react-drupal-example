<?php

namespace Drupal\Tests\views_ui\Functional;

use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\views\Views;

/**
 * Tests the UI of row plugins.
 *
 * @group views_ui
 * @see \Drupal\views_test_data\Plugin\views\row\RowTest.
 */
class RowUITest extends UITestBase {

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['test_view'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests changing the row plugin and changing some options of a row.
   */
  public function testRowUI() {
    $view_name = 'test_view';
    $view_edit_url = "admin/structure/views/view/$view_name/edit";

    $row_plugin_url = "admin/structure/views/nojs/display/$view_name/default/row";
    $row_options_url = "admin/structure/views/nojs/display/$view_name/default/row_options";

    $this->drupalGet($row_plugin_url);
    $this->assertSession()->fieldValueEquals('row[type]', 'fields');

    $edit = [
      'row[type]' => 'test_row',
    ];
    $this->drupalPostForm(NULL, $edit, 'Apply');
    // Make sure the custom settings form from the test plugin appears.
    $this->assertSession()->fieldExists('row_options[test_option]');
    $random_name = $this->randomMachineName();
    $edit = [
      'row_options[test_option]' => $random_name,
    ];
    $this->drupalPostForm(NULL, $edit, 'Apply');
    $this->drupalGet($row_options_url);
    // Make sure the custom settings form field has the expected value stored.
    $this->assertSession()->fieldValueEquals('row_options[test_option]', $random_name);

    $this->drupalPostForm($view_edit_url, [], 'Save');
    $this->assertSession()->linkExists('Test row plugin', 0, 'Make sure the test row plugin is shown in the UI');

    $view = Views::getView($view_name);
    $view->initDisplay();
    $row = $view->display_handler->getOption('row');
    $this->assertEqual($row['type'], 'test_row', 'Make sure that the test_row got saved as used row plugin.');
    $this->assertEqual($row['options']['test_option'], $random_name, 'Make sure that the custom settings field got saved as expected.');

    $this->drupalPostForm($row_plugin_url, ['row[type]' => 'fields'], 'Apply');
    $this->drupalGet($row_plugin_url);
    $this->assertSession()->statusCodeEquals(200);
    // Make sure that 'fields' was saved as the row plugin.
    $this->assertSession()->fieldValueEquals('row[type]', 'fields');

    // Ensure that entity row plugins appear.
    $view_name = 'content';
    $row_plugin_url = "admin/structure/views/nojs/display/$view_name/default/row";
    $row_options_url = "admin/structure/views/nojs/display/$view_name/default/row_options";

    $this->drupalGet($row_plugin_url);
    $this->drupalPostForm(NULL, ['row[type]' => 'entity:node'], 'Apply');
    $this->assertSession()->addressEquals($row_options_url);
    // Make sure the custom settings form from the entity row plugin appears.
    $this->assertSession()->fieldValueEquals('row_options[view_mode]', 'teaser');

    // Change the teaser label to have markup so we can test escaping.
    $teaser = EntityViewMode::load('node.teaser');
    $teaser->set('label', 'Teaser <em>markup</em>');
    $teaser->save();
    $this->drupalGet('admin/structure/views/view/frontpage/edit/default');
    $this->assertSession()->assertEscaped('Teaser <em>markup</em>');
  }

}
