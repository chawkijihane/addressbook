<?php
namespace CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GoogleAnalyticsExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'google_analytics' => new \Twig_Function_Method($this, 'getAnalytics', array('is_safe' => array('html')))
        );
    }

    public function getAnalytics()
    {
        if ($this->container->get('request')->server->get('REDIRECT_HTTPS') == 'on') {
            $GACode = $this->container->getParameter('httpsgacode');
        } else {
            $GACode = $this->container->getParameter('httpgacode');
        }

        if ($GACode != null) {

            $codeToReturn = '<script>' .
                '(function (i, s, o, g, r, a, m) {' .
                'i[\'GoogleAnalyticsObject\'] = r;' .
                'i[r] = i[r] || function () {' .
                '(i[r].q = i[r].q || []).push(arguments)' .
                '}, i[r].l = 1 * new Date();' .
                'a = s.createElement(o),' .
                'm = s.getElementsByTagName(o)[0];' .
                'a.async = 1;' .
                'a.src = g;' .
                'm.parentNode.insertBefore(a, m)' .
                '})(window, document, \'script\', \'//www.google-analytics.com/analytics.js\', \'ga\');' .
                'ga(\'create\', \'' . $GACode . '\', \'auto\');' .
                'ga(\'send\', \'pageview\');' .
                '</script>';
        } else {
            $codeToReturn = null;
        }

        return $codeToReturn;
    }

    public function getName()
    {
        return 'google_analytics_extension';
    }
}
