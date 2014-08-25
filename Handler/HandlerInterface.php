<?php

namespace FDevs\FileBundle\Handler;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface HandlerInterface
{
    /**
     * upload
     *
     * @param UploadedFile $uploadedFile
     *
     * @return \FDevs\FileBundle\Model\File
     */
    public function upload(UploadedFile $uploadedFile);

    /**
     * delete
     * @param string $name
     *
     * @return bool|string
     */
    public function delete($name);

    /**
     * get Handler Name
     *
     * @return string
     */
    public function getName();
}
