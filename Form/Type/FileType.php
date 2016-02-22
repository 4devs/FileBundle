<?php

namespace FDevs\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    /** @var string */
    private $default;

    /** @var array */
    private $filesystems = [];

    /**
     * init
     *
     * @param string $default
     * @param array  $filesystems
     */
    public function __construct($default, array $filesystems = [])
    {
        $this->default = $default;
        $this->filesystems = $filesystems;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined([
                'validation_options',
                'handler_name',
                'filesystem',
            ])
            ->setDefaults([
                'validation_options' => $this->filesystems[$this->default]['validation_options'],
                'handler_name' => 'file',
                'filesystem' => $this->default,
                'multiple' => false,
                'translation_domain' => 'FDevsFileBundle',
                'label' => 'label.file',
                'default_protocol' => null,
                'attr' => ['novalidate' => true],
            ])
            ->addAllowedTypes('validation_options'['array'])
            ->addAllowedTypes('handler_name'['string'])
            ->addAllowedTypes('filesystem'['string'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $filesystem = $this->filesystems[$options['filesystem']];
        if ($options['multiple']) {
            $view->vars['full_name'] .= '[]';
            $view->vars['attr']['multiple'] = 'multiple';
        }
        $view->vars['filesystem'] = $options['filesystem'];
        $view->vars['file_key'] = $form->getData() ? pathinfo($form->getData(), PATHINFO_BASENAME) : 'empty';
        $view->vars['handler_name'] = $options['handler_name'];
        $view->vars['validation_options'] = array_replace(
            $filesystem['validation_options'],
            $options['validation_options']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['multipart'] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return UrlType::class;
    }
}
