<?php

namespace CoreBundle\Util;

class SuffixedUrlResolver implements BaseUrlResolverInterface
{
    private $delegate;
    private $suffix;

    /**
     * @param BaseUrlResolverInterface $delegate
     * @param string                   $suffix
     */
    public function __construct(BaseUrlResolverInterface $delegate, $suffix)
    {
        $this->delegate = $delegate;
        $this->suffix = $suffix;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseAssetUrl()
    {
        if (empty($this->suffix)) {
            return $this->delegate->getBaseAssetUrl();
        }

        return sprintf('%s/%s', $this->delegate->getBaseAssetUrl(), $this->suffix);
    }
}
