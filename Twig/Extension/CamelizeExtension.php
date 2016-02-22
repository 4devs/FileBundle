<?php

namespace FDevs\FileBundle\Twig\Extension;

use Doctrine\Common\Inflector\Inflector;

class CamelizeExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [new \Twig_SimpleFilter('camelize', [$this, 'camelizeFilter'])];
    }

    /**
     * camelize Filter.
     *
     * @param $value
     *
     * @return string
     */
    public function camelizeFilter($value)
    {
        return Inflector::camelize($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'f_devs_camelize';
    }
}
