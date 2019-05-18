@lightning @lightning_layout @api
Feature: Creating and editing landing pages visually

  @1e244c89
  Scenario: Layout Builder is enabled for individual landing pages
    Given I am logged in as a landing_page_creator
    And landing_page content:
      | title  | path    |
      | Foobar | /foobar |
    When I visit "/foobar"
    Then I should see the link "Layout"

  @orca_ignore
  Scenario: Using a pre-built layout
    Given I am logged in as a landing_page_creator
    When I visit "/node/add/landing_page"
    And I enter "Aesop's Fables" for "Title"
    And I enter "Misery loves company." for "Body"
    And I select "Two-column" from "Layout"
    And I press "Save"
    Then the page should be using a two-column layout
