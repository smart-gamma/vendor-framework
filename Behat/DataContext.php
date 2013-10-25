<?php

namespace LaMelle\Framework\Behat;

use Behat\Gherkin\Node\TableNode;
use Sylius\Bundle\SandboxBundle\Entity\User;

/**
 * Data context.
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
class DataContext extends BaseContext
{
    /**
     * @Given /^there are following users:$/
     * @Given /^the following users exist:$/
     */
    public function thereAreFollowingUsers(TableNode $table)
    {
        $em = $this->getEntityManager();
        $userManager = $this->kernel->getContainer()->get('fos_user.user_manager');

        foreach ($table->getHash() as $data) {
            $data = array_merge(
                array(
                    'enabled'     => true,
                ),
                $data
            );

            $user = $userManager->createUser();
            $user->setUsername($data['username']);
            $user->setEmail(sprintf('%s@test.com', strtolower($data['username'])));
            $user->setPlainPassword($data['password']);
            $user->setEnabled($data['enabled']);

            $userManager->updateUser($user, false);
        }

        $em->flush();
    }
}
