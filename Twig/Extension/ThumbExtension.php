<?php
namespace FDevs\FileBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ThumbExtension extends \Twig_Extension
{


    private $container;
    private $filesystem;
    private $config = [];

    /**
     * init
     *
     * @param array              $config
     * @param string             $filesystem
     * @param ContainerInterface $container
     */
    public function __construct(array $config, $filesystem, ContainerInterface $container)
    {
        $this->filesystem = $filesystem;
        $this->config = $config;
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('thumb', [$this, 'thumbFunction']),
        ];
    }

    /**
     * thumb function
     *
     * @param string $file
     * @param string $thumbName
     * @param string $filesystem
     *
     * @return mixed
     */
    public function thumbFunction($file, $thumbName, $filesystem = '')
    {
        $webPath = $this->getWebPath($filesystem);

        return str_replace($webPath, $webPath . $thumbName . '_', $file);
    }

    public function getWebPath($filesystem = '')
    {
        return ($filesystem && isset($this->config[$filesystem])) ? $this->config[$filesystem]['web_path'] : $this->config[$this->filesystem]['web_path'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'f_devs_file_thumb';
    }

}
