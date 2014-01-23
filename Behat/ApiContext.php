<?php

namespace Gamma\Framework\Behat;

use Behat\CommonContexts\WebApiContext;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Api context additional methods for behat common api context
 *
 * @authorEvgeniy Kuzmin <jekccs@gmail.com>
 */
class ApiContext extends WebApiContext
{
    /**
     * Checks that response body contains specific text from  PyString .
     *
     * @param PyStringNode $jsonString
     *
     * @Then /^(?:the )?response should contain part of text$/
     */
    public function theResponseShouldContainPartOfText(PyStringNode $jsonString)
    {
        $text = json_decode($this->replacePlaceHolder($jsonString->getRaw()), true);
        assertRegExp('/'.preg_quote($text).'/', $this->getBrowser()->getLastResponse()->getContent());
    } 
}
