<?php

namespace App\Tests;

use Symfony\Component\HttpKernel\Client as BaseClient;
use Symfony\Component\BrowserKit\Request;

/**
 * Class ApiClient
 * @package App\Tests
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */
class ApiClient extends BaseClient
{
    /**
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @param bool $changeHistory
     * @return \Symfony\Component\DomCrawler\Crawler|\Symfony\Component\HttpFoundation\Response
     */
    public function request(string $method, string $uri, array $parameters = array(), array $files = array(), array $server = array(), string $content = null, bool $changeHistory = true)
    {
        $uri = $this->getAbsoluteUri($uri);
        $server = array_merge($this->server, $server);

        if (isset($server['HTTPS'])) {
            $uri = preg_replace('{^'.parse_url($uri, PHP_URL_SCHEME).'}', $server['HTTPS'] ? 'https' : 'http', $uri);
        }

        $server['HTTPS'] = 'https' == parse_url($uri, PHP_URL_SCHEME);

        $this->request = $this->doRequest($this->filterRequest(new Request($uri, $method, $parameters, [], [], $server, $content)));

        return $this->request;
    }


}