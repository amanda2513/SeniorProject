Feature: Projects Page

  Background:
    Given I am at a page
    And I have logged in a user

  Scenario: Add Participant
    Given a user is on the projects participant page
    And User click on Add Participant
    When User be able to register a participant with details
#    |Last Name|First Name|Department|Project Title|Project Description|
    Then the user should be added to the participants tab

  Scenario: Add Participant with out mandatory parameter
    Given a user is on the projects participant page
    And User click on Add Participant
    When User be able to register a participant with out email
  #    |Last Name|First Name|Department|Project Title|Project Description|
    Then an "Email" missing error should be thrown


  Scenario: Add Participant with out optional parameter
    Given a user is on the projects participant page
    And User click on Add Participant
    When User be able to register a participant without optional parameters
  #    |Last Name|First Name|Department|Project Title|Project Description|
    Then the user should be added to the participants tab


  Scenario: sort by Last Name
    Given a user is on the projects participant page
    When User click on sort Participant
  #    |Last Name|First Name|Department|Project Title|Project Description|
    Then the participants list should be sorted accordingly


  Scenario: edit user
    Given a user has registered a participant
    And User click on the edit icon
  #    |Last Name|First Name|Department|Project Title|Project Description|
    When the user first name is edited
    Then in the participant tab user information should be updated


  Scenario: Add Judge from Participant
    Given a user is on the projects participant page
    And User click on Add Participant
    When User be able to register a judge with details
  #    |Last Name|First Name|Department|Project Title|Project Description|
    Then the user should be added to the judge tab


  Scenario: Add Judge from Judge Page
    Given a user is on the projects judge page
    When User click on Add judge
    Then the new user type should be judge


  Scenario: Add Judge from Judge Page
    Given a user is on the projects judge page
    And User click on Add judge
    When the user enter all the required information
    Then judge should be added
    And the same details should be present in the judge table


  Scenario: Check Edit button on judge page
    Given a judge is added to the judge table
    When I click on modify judge details
    Then I should see same judges details


  Scenario: Edit Participant Details
    Given a user is on the projects participant page
    And User click on Add Participant
    And User be able to register a participant with details
  #    |Last Name|First Name|Department|Project Title|Project Description|
    When User click on the edit icon
    Then the user should be taken to user modification page


  Scenario: Search Participant Details
    Given a user is on the projects participant page
    When user search for the participant name
    Then the user should see the list of search result

  @wip
  Scenario: Clear search result
    Given a user has searched participant name
    When the user clicks on clear
    Then the results should be cleared
