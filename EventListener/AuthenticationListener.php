<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\EventListener;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Vespolina\Partner\Manager\PartnerManagerInterface;
use Vespolina\CommerceBundle\Process\ProcessManagerInterface;

class AuthenticationListener
{

    protected $partnerManager;
    protected $session;

    public function __construct( SessionInterface $session,
                                 PartnerManagerInterface $partnerManager = null,
                                 ProcessManagerInterface $processManager) {

        $this->partnerManager = $partnerManager;
        $this->processManager = $processManager;
        $this->session = $session;
    }

    /**
     * Called when an user is authenticated
     *
     */
    function onAuthenticationSuccess(AuthenticationEvent $event)
    {

        $user = $event->getAuthenticationToken()->getUser();
        $this->session->set('customer', $user->getPartner());

    }
}