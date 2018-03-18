<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 *
 * @RouteResource("login", pluralize=false)
 */
class ApiLoginController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @throws \DomainException
     */
    public function postAction()
    {
        throw new \DomainException('Route should be handled by Lexik JWT Authentication Bundle');
    }
}