Feature: Manage Users

  Background:
    Given I am at a page
    And I have logged in a user


  Scenario: Clean up participants
    Given I am on Participants page in manage users page
    When I delete all the entries in participant table
    Then I should see no entries in the table in the participant table

  Scenario: Add participant and delete the particiant
    Given a user is on the projects participant page
    And User click on Add Participant
    When User be able to register a participant with details
  #    |Last Name|First Name|Department|Project Title|Project Description|
    Then the user should be added to the participants tab
    And user should be able to delete the participant

  Scenario: Add participant and edit the particiant
    Given a user is on the projects participant page
    And User click on Add Participant
    When User be able to register a participant with details
  #    |Last Name|First Name|Department|Project Title|Project Description|
    Then the user should be able to edit participant

  @bug
  Scenario: edit the particiant project category should not be zero
    Given a user is on the projects participant page
    And User click on Add Participant
    When User be able to register a participant with details
  #    |Last Name|First Name|Department|Project Title|Project Description|
    Then the user should be able to edit participant
    And the project category should not be zero

  @wip
  Scenario: modify Judge from Judge Page
    Given a judge is added to the judge table
    When I click on modify judge details on manage user page
    And user click on add Score entry user

  Scenario: Delete Judge from manage user page
    Given a judge is added to the judge table
    Then user should be able to delete the judge


  Scenario: Add Judge from manage user page
    Given a user is on the judge manage user page
    And User click on Add judge
    When the user enter all the required information
    Then judge should be added
    And the same details should be present in the judge table


  Scenario: Add Score Entry User
    Given a user is on the score Entry User page
    And user click on add Score entry user
    When User be able to register a score entry user with details


  Scenario: Modify Score Entry User
    Given a user is on the score Entry User page
    When user click on modify Score entry user
    Then the user should be able to edit first name
    And the same should be seen in the score entry user table




  Scenario: Delete Score Entry User
    Given a score entry user is added to the score entry user table
    Then user should be able to delete the score entry user


  Scenario: Add Admin
    Given a user is on the admin page
    And user click on add admin
    When User be able to register admin with details


  Scenario: Modify Admin
    Given a score entry user is added to the judge table
    When I click on modify admin details on manage user page
    Then the user should be able to edit first name
    And the same should be seen in the admin table


  Scenario: Delete Admin
    Given a Admin is added to the score entry user table
    Then user should be able to delete the Admin


