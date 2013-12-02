<?php

namespace Gamma\Framework\Behat;

use Behat\Behat\Context\Step;

/**
 * Moving around page context.
 *
 * @authorEvgeniy Kuzmin <jekccs@gmail.com>
 */
class PagesContext extends BaseContext
{   
    /**
     * @Then /^I should be on login page$/
     */
    public function iShouldBeOnLoginPage()
    {
        return $this->iShouldBeOnRoute('fos_user_security_login');
    }

    /**
     * @Then /^I should be on registration page$/
     */
    public function iShouldBeOnRegistrationPage()
    {
        return $this->iShouldBeOnRoute('fos_user_registration_register');
    }

    private function iAmOnRoute($route, array $parameters = array())
    {
        return $this->iAmOn($this->generateUrl($route, $parameters));
    }

    private function iAmOn($path)
    {
        return new Step\Given(sprintf('I am on "%s"', $path));
    }

    private function iShouldBeOnRoute($route, array $parameters = array())
    {
        return $this->iShouldBeOn($this->generateUrl($route, $parameters));
    }

    private function iShouldBeOn($path)
    {
        return new Step\Given(sprintf('I should be on "%s"', $path));
    }
}
