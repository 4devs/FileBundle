<?php

namespace FDevs\FileBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $template = 'FDevsFileBundle:Form:fields.html.twig';
        $resources = $container->getParameter('twig.form.resources');

        if (!in_array($template, $resources)) {
            $resources[] = 'FDevsFileBundle:Form:fields.html.twig';
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}
