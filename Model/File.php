<?php

namespace FDevs\FileBundle\Model;

use Cocur\Slugify\Slugify;

class File implements \Serializable
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $error;
    /** @var int */
    protected $size;
    /** @var string */
    protected $url;
    /** @var string */
    protected $thumbnailUrl;
    /** @var string */
    protected $deleteUrl;
    /** @var string */
    protected $deleteType;
    /** @var string */
    protected $type;
    /** @var string */
    protected $pathname = '';

    /**
     * init.
     *
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type)
    {
        $this->type = $type;
        $name = pathinfo($name);
        $this->name = Slugify::create()->slugify($name['filename'])
            .(empty($name['extension']) ? '' : '.'.$name['extension']);
    }

    /**
     * @return string
     */
    public function getDeleteType()
    {
        return $this->deleteType;
    }

    /**
     * @param string $deleteType
     *
     * @return $this
     */
    public function setDeleteType($deleteType)
    {
        $this->deleteType = $deleteType;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->deleteUrl;
    }

    /**
     * @param string $deleteUrl
     *
     * @return $this
     */
    public function setDeleteUrl($deleteUrl)
    {
        $this->deleteUrl = $deleteUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * @param string $thumbnailUrl
     *
     * @return $this
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathname()
    {
        return $this->pathname;
    }

    /**
     * @param string $pathname
     *
     * @return $this
     */
    public function setPathname($pathname)
    {
        $this->pathname = $pathname;

        return $this;
    }

    /**
     * to Array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'type' => $this->type,
            'name' => $this->name,
            'size' => $this->size,
            'thumbnailUrl' => $this->thumbnailUrl,
            'url' => $this->url,
            'deleteUrl' => $this->deleteUrl,
            'deleteType' => $this->deleteType,
            'error' => $this->error,
        ];

        return array_filter(
            $data,
            function ($var) {
                return (bool) ($var);
            }
        );
    }

    /**
     * from Array.
     *
     * @param array $array
     *
     * @return $this
     */
    public function fromArray(array $array)
    {
        foreach ($array as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $this->fromArray(unserialize($serialized));
    }
}
