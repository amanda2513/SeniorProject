Feature: Participant Registration

#  still the email and password field is common for sign in and to enter the user register details this needs to be fixed

  @bug
  Scenario: Register a participant
    Given I am at a page
    When User register a participant with details
    Then the user should be registered successfully


  Scenario: verify user on the main page
    Given I am at a page
    Then user should see a option to enter user details
    And should have the signin button


  Scenario: verify user on the main page
    Given I am at a page
    Then user should see a option to register as participant
#    And user should see a option to register as judge

  Scenario: Home page header with logo and link to wayne website
    Given I am at a page
    Then the page header should have wayne logo
    And the logo should have link to wayne website

