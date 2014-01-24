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
    public function theResponseShouldContainPartOfText(PyStringNode $string)
    {
        $text = preg_quote($this->replacePlaceHolder($string));
        $text = str_replace('%__REG_EXP_MATCH_ANY__%', '(.*)', $text);
        assertRegExp('/'.$text.'/is', $this->getBrowser()->getLastResponse()->getContent());
    } 
}
