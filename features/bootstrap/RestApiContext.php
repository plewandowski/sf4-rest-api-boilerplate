<?php

use App\Tests\ApiClient;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit\Framework\Assert as Assertions;

/**
 * Class RestApiContext
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */
class RestApiContext implements Context
{
    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $exceptions = [];

    /**
     * RestApiContext constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->kernel->boot();
        $this->client = new ApiClient($this->kernel);
    }

    /**
     * @When I have forgotten to set the :header
     */
    public function iHaveForgottenToSetThe($header)
    {
        $this->addHeader($header, null);
    }

    /**
     * Authenticate and obtain JWT token
     *
     * @param string $username
     * @param string $password
     *
     * @Given /^I am successfully authenticated as a REST user: "([^"]*)" with password: "([^"]*)"$/
     */
    public function iAmSuccessfullyAuthenticatedAsRestUserWithPassword($username, $password)
    {
        $this->iSendARequest('POST', '/api/login',
            [
                'username' => $username,
                'password' => $password,
            ]
        );

        $this->theResponseCodeShouldBe(200);

        $responseBody = json_decode($this->response->getContent(), true);
        $this->token = $responseBody['token'];
    }

    /**
     * Sets a HTTP Header.
     *
     * @param string $name header name
     * @param string $value header value
     *
     * @Given /^I set header "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetHeaderWithValue($name, $value)
    {
        $this->addHeader($name, $value);
    }

    /**
     * Sends HTTP request to specific URL with json data.
     *
     * @param string $method
     * @param string $url
     *
     * @param array $data
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url, $data = [])
    {
        $headers                 = $this->headers;
        $headers['CONTENT_TYPE'] = 'application/json';

        if ($this->token) {
            $headers['HTTP_Authorization'] = sprintf('Bearer %s', $this->token);
        }

        $this->response = $this->client->request(
            $method,
            $url,
            [],
            [],
            $headers,
            $data ? json_encode($data) : null
        );

    }

    /**
     * Sends HTTP request to specific URL with json data.
     *
     * @param string $method
     * @param string $url
     * @param PyStringNode $jsonData
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with json data:$/
     */
    public function iSendARequestWithJsonData($method, $url, PyStringNode $jsonData)
    {
        $headers                 = $this->headers;
        $headers['CONTENT_TYPE'] = 'application/json';

        if ($this->token) {
            $headers['HTTP_Authorization'] = sprintf('Bearer %s', $this->token);
        }

        $this->response = $this->client->request(
            $method,
            $url,
            [],
            [],
            $headers,
            $jsonData
        );

    }

    /**
     * Checks that response has specific status code.
     *
     * @param string $code status code
     *
     * @Then the response code should be :arg1
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = (int)$code;
        $actual   = (int)$this->response->getStatusCode();

        Assertions::assertSame($expected, $actual);
    }

    /**
     * Checks that response body contains specific text.
     *
     * @param string $text
     *
     * @Then /^(?:the )?response should contain "((?:[^"]|\\")*)"$/
     */
    public function theResponseShouldContain($text)
    {
        $expectedRegexp = '/' . preg_quote($text) . '/i';
        $actual         = (string)$this->response->getContent();
        Assertions::assertRegExp($expectedRegexp, $actual);
    }

    /**
     * @param $name
     * @param $value
     */
    protected function addHeader($name, $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * @Then /^the response json should have key "([^"]*)" which equals to "([^"]*)"$/
     * @param string $key
     * @param string $val
     */
    public function theResponseJsonShouldHaveKeyWhichEqualsTo($key, $val)
    {
        $jsonData = json_decode($this->response->getContent(), true);

        Assertions::assertArrayHasKey($key, $jsonData);
        Assertions::assertEquals((string)$val, (string)$jsonData[$key]);
    }

    /**
     * @Then /^the response json should equals to:$/
     * @param PyStringNode $responseJson
     */
    public function theResponseJsonShouldEqualsTo(PyStringNode $responseJson)
    {
        Assertions::assertJson($responseJson);
        Assertions::assertJson($this->response->getContent());
        Assertions::assertJsonStringEqualsJsonString($responseJson, $this->response->getContent());
    }

}