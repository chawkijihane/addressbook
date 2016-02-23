<?php

namespace CoreBundle\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RequestUrlResolver implements BaseUrlResolverInterface
{
    private $container;
    private $defaultBaseUrl;

    /**
     * @param ContainerInterface $container
     * @param string             $defaultBaseUrl
     */
    public function __construct(ContainerInterface $container, $defaultBaseUrl)
    {
        $this->container = $container;
        $this->defaultBaseUrl = $defaultBaseUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseAssetUrl()
    {
        if ($this->container->isScopeActive('request')) {
            /** @var $request \Symfony\Component\HttpFoundation\Request */
            $request = $this->container->get('request');

            return $request->getSchemeAndHttpHost() . $request->getBasePath();
        }

        return $this->defaultBaseUrl;
    }
}
