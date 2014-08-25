<?php

namespace FDevs\FileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('f_devs_file');
        $rootNode
            ->children()
                ->scalarNode('default')->isRequired(true)->end()
            ->end();
        $this->addFilesystemsSection($rootNode);

        return $treeBuilder;
    }

    private function addFilesystemsSection(ArrayNodeDefinition $node)
    {
        $supportedTypes = ['crop', 'resize', 'fit'];
        $node
            ->fixXmlConfig('filesystem')
            ->children()
                ->arrayNode('filesystems')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('web_path')->isRequired(true)->end()
                        ->scalarNode('prefix')->end()
                        ->scalarNode('gaufrette_filesystem')->isRequired(true)->end()
                        ->arrayNode('validation_options')
                            ->children()
                                ->scalarNode('accept_file_types')->defaultValue('/(\.|\/)(gif|jpe?g|png)$/i')->end()
                                ->integerNode('max_file_size')->end()
                                ->integerNode('min_file_size')->end()
                                ->integerNode('max_number_of_files')->end()
                                ->booleanNode('disableValidation')->defaultFalse()->end()
                            ->end()
                        ->end()
                        ->arrayNode('thumbs')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->integerNode('height')->end()
                                    ->integerNode('width')->end()
                                    ->scalarNode('type')->defaultValue('fit')
                                        ->validate()
                                        ->ifNotInArray($supportedTypes)
                                        ->thenInvalid('The type %s is not supported. Please choose one of '.json_encode($supportedTypes))
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

}
