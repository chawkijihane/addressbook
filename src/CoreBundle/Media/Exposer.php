<?php

namespace CoreBundle\Media;


use CoreBundle\Entity\Email;
use CoreBundle\Entity\User;
use CoreBundle\Util\BaseUrlResolverInterface;


class Exposer
{
    private $baseUrlResolver;

    /**
     * @param BaseUrlResolverInterface $baseUrlResolver
     */
    public function __construct(BaseUrlResolverInterface $baseUrlResolver)
    {
        $this->baseUrlResolver = $baseUrlResolver;
    }

    private function getPredefinedUrl($path)
    {
        if (null === $path) {
            return null;
        }

        return sprintf('%s/%s', $this->baseUrlResolver->getBaseAssetUrl(), $path);
    }
}
