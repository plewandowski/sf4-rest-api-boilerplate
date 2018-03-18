Feature:
  In order to manage books in database I should be able list,create,update and delete books with REST api

  Background:
    Given there are REST Users with the following details:
      | id | username | email                            | password |
      | 1  | user1    | p.lewandowski+user1@madcoders.pl | pass1    |

    Given there are Books with the following details:
      | id | isbn          | title                                    | price |
      | 1  | 9782123456803 | Existing book                            | 99.95 |
      | 1  | 9780136019701 | Another existing book                    | 8.50  |

  Scenario: I try to create book with valid json payload
    Given I am successfully authenticated as a REST user: "user1" with password: "pass1"
    When I send a POST request to "/api/v1/books" with json data:
        """
          {
            "isbn": "9783161484100",
            "title": "Test book",
            "price": 33.33
          }
        """
    Then the response code should be 200
    Then the response json should have key "isbn" which equals to "9783161484100"
    Then the response json should have key "title" which equals to "Test book"
    Then the response json should have key "price" which equals to "33.33"


  Scenario: I try to create book with invalid ISBN
    Given I am successfully authenticated as a REST user: "user1" with password: "pass1"
    When I send a POST request to "/api/v1/books" with json data:
        """
          {
            "isbn": "9783161484101",
            "title": "Test book",
            "price": 33.33
          }
        """
    Then the response code should be 400

  Scenario: I try to list existing books
    Given I am successfully authenticated as a REST user: "user1" with password: "pass1"
    When I send a GET request to "/api/v1/books"
    Then the response code should be 200
    Then the response json should equals to:
        """
        [
          {
            "isbn": "9782123456803",
            "title": "Existing book",
            "price": 99.95
          },
          {
            "isbn": "9780136019701",
            "title": "Another existing book",
            "price": 8.50
          }
        ]
        """

  Scenario: I try to to request single book
    Given I am successfully authenticated as a REST user: "user1" with password: "pass1"
    When I send a GET request to "/api/v1/books/9782123456803"
    Then the response code should be 200
    Then the response json should have key "isbn" which equals to "9782123456803"
    Then the response json should have key "title" which equals to "Existing book"
    Then the response json should have key "price" which equals to "99.95"


  Scenario: I try to delete book
    Given I am successfully authenticated as a REST user: "user1" with password: "pass1"
    When I send a DELETE request to "/api/v1/books/9782123456803"
    Then the response code should be 204

    Given I send a GET request to "/api/v1/books"
    Then the response code should be 200
    Then the response json should equals to:
        """
        [
          {
            "isbn": "9780136019701",
            "title": "Another existing book",
            "price": 8.50
          }
        ]
        """

  Scenario: I try to delete book which does not exist
    Given I am successfully authenticated as a REST user: "user1" with password: "pass1"
    When I send a DELETE request to "/api/v1/books/11111111111"
    Then the response code should be 404


  Scenario: I update books with PATH and PUT methods
    Given I am successfully authenticated as a REST user: "user1" with password: "pass1"

    When I send a PUT request to "/api/v1/books/9782123456803" with json data:
        """
          {
            "isbn": 9782123456803,
            "title": "Existing book updated",
            "price": 33.33
          }
        """
    Then the response code should be 200


    When I send a PATCH request to "/api/v1/books/9780136019701" with json data:
        """
          {
            "price": 901.00
          }
        """
    Then the response code should be 200


    Given I send a GET request to "/api/v1/books"
    Then the response code should be 200
    Then the response json should equals to:
        """
        [
          {
            "isbn": "9782123456803",
            "title": "Existing book updated",
            "price": 33.33
          },
          {
            "isbn": "9780136019701",
            "title": "Another existing book",
            "price": 901.00
          }
        ]
        """

  Scenario: I try to access api without valid JWT token
    When I send a GET request to "/api/v1/books"
    Then the response code should be 401



