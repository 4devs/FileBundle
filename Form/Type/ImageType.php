<?php

namespace FDevs\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'fdevs_image';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'fdevs_file';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['thumbs'] = $options['thumbs'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(['handler_name' => 'image', 'thumbs' => []])
            ->setOptional(['thumbs'])
            ->addAllowedTypes(['thumbs' => 'array']);

    }
}
