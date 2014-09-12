<?php

namespace FDevs\FileBundle\Handler;

use FDevs\FileBundle\Model\File;
use Gaufrette\Filesystem;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class UploadHandler implements HandlerInterface
{
    /** @var FilesystemMap */
    private $filesystemMap;
    /** @var array */
    private $config = [];
    /** @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface */
    private $urlGenerator;

    /**
     * init
     *
     * @param FilesystemMap         $filesystemMap
     * @param UrlGeneratorInterface $urlGenerator
     * @param string                $defaultFilesystemName
     * @param array                 $configFilesystems
     */
    public function __construct(
        FilesystemMap $filesystemMap,
        UrlGeneratorInterface $urlGenerator,
        $defaultFilesystemName,
        array $configFilesystems
    ) {
        $this->setConfig($configFilesystems[$defaultFilesystemName]);

        $this->urlGenerator = $urlGenerator;
        $this->filesystemMap = $filesystemMap;

    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'file';
    }

    /**
     * {@inheritDoc}
     */
    public function upload(UploadedFile $uploadedFile)
    {
        $file = new File($uploadedFile->getClientOriginalName(), $uploadedFile->getMimeType());
        try {
            if (!preg_match($this->getAllowedType(), $uploadedFile->getMimeType())) {
                throw new \Exception(sprintf('type "%s" not allowed', $uploadedFile->getMimeType()));
            }
            $size = $this->getFilesystem()->write($file->getName(), file_get_contents($uploadedFile->getPathname()));

            $file->setPathname($uploadedFile->getPathname());
            $file->setSize($size);
            $file->setDeleteType('DELETE');
            $file->setDeleteUrl(
                $this->urlGenerator->generate(
                    'f_devs_file_delete',
                    ['key' => $file->getName(), 'handlerName' => $this->getName()],
                    Router::ABSOLUTE_URL
                )
            );
            $file->setUrl($this->config['web_path'] . $file->getName());
        } catch (\Exception $e) {
            $file->setError($e->getMessage());
        }

        return $file;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($name)
    {
        try {
            $return = $this->getFilesystem()->delete($name);
        } catch (\Exception $e) {
            $return = $e->getMessage();
        }

        return $return;
    }

    /**
     * set Config
     *
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config = [])
    {
        $this->config = $config;

        return $this;
    }

    /**
     * write
     *
     * @param string $name
     * @param string $content
     *
     * @return int size
     */
    protected function write($name, $content)
    {
        return $this->getFilesystem()->write($name, $content);
    }

    /**
     * get Filesystem
     *
     * @param string $name
     *
     * @return Filesystem
     */
    protected function getFilesystem($name = '')
    {
        return $this->filesystemMap->get($name ?: $this->config['gaufrette_filesystem']);
    }

    /**
     * get Thumbs
     *
     * @return array
     */
    protected function getThumbs()
    {
        return isset($this->config['thumbs']) ? $this->config['thumbs'] : [];
    }

    /**
     * get Url Generator
     *
     * @return UrlGeneratorInterface
     */
    protected function getUrlGenerator()
    {
        return $this->urlGenerator;
    }

    /**
     * get Allowed Type
     *
     * @return string
     */
    protected function getAllowedType()
    {
        return $this->config['validation_options']['accept_file_types'];
    }

}
