Feature: System Settings

  Background:
    Given I am at a page
    And I have logged in a user

  @wip
  Scenario: change Systems settings
    Given I am on the system setting page
    When I change System settings
    And click on save
    Then the systems settings should be saved

  @wip
  Scenario: verify changed Systems settings
    Given I am on the system setting page to verify changed settings
    Then the systems settings should be the one we changed earlier

  Scenario: Project category page
    Given I am on project category page
    When I click on Add category button
    Then I should see "Add Subcategory", "Submit", "Add Criteria" buttons

  Scenario: Project category page data fill
    Given I am on project category page
    And I click on Add category button
    When I enter all the fields
    And Click on "Submit" Button
    Then I should see category and subcategory added

  Scenario: Project category page data fill with no category name
    Given I am on project category page
    And I click on Add category button
    When I enter all the fields except category name field
    And Click on "Submit" Button
    Then I should see error "The Category Name field is required."

  Scenario: Project category page data fill with no subcategory name
    Given I am on project category page
    And I click on Add category button
    When I enter all the fields except subcategory name field
    And Click on "Submit" Button
    Then I should see error "The Subcategory Name field is required."

  Scenario: Project category page data fill with no subcategory description
    Given I am on project category page
    And I click on Add category button
    When I enter all the fields except subcategory description field
    And Click on "Submit" Button
    Then I should see error "The Subcategory Criterion - Description field is required."

  Scenario: Project category page data fill with no subcategory points
    Given I am on project category page
    And I click on Add category button
    When I enter all the fields except subcategory points field
    And Click on "Submit" Button
    Then I should see error "The Subcategory Criterion - Points Possible field is required."

  Scenario: project category Add Criteria
    Given I am on project category page
    And I click on Add category button
    When I Add extra Criteria along with other details to add a category
    And Click on "Submit" Button
    Then I should be able to see criteria point as the sum of 2 criteria

  Scenario: Add category and subcategory
    Given I am on project category page
    And I click on Add category button
    And I have filled in criteria details
    When I add a subcategory
    And Click on "Submit" Button
    Then I should see category and two subcategory added

  Scenario: Delete a added category
    Given I am on project category page
    And I click on Add category button
    When I enter all the fields
    And Click on "Submit" Button
    Then I should see category and subcategory added
    When I delete the added category
    Then I should not see the added category

  @wip
  Scenario: Project category page cleanup
    Given I am on project category page
    When I delete all the entries
    Then I should see no entries in the table
