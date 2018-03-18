
Feature:
  In order to access API i need to authenticate as REST user

  Background:
    Given there are REST Users with the following details:
      | id | username | email                            | password |
      | 1  | user1    | p.lewandowski+user1@madcoders.pl | pass1    |


  Scenario: I try to authenticate with valid credentials
    When I send a POST request to "/api/login" with json data:
        """
        {
          "username": "user1",
          "password": "pass1"
        }
        """
    Then the response code should be 200
    Then the response should contain "token"

  Scenario: I try authenticate with invalid credentials
    When I send a POST request to "/api/login" with json data:
        """
        {
          "username": "user1",
          "password": "WRONG PASSWORD"
        }
        """
    Then the response code should be 401


  Scenario: I try authenticate with invalid json
    When I send a POST request to "/api/login" with json data:
        """
        {
        }
        """
    Then the response code should be 400
