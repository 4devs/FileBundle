<?php

namespace FDevs\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UploadController extends Controller
{
    public function uploadAction(Request $request, $handlerName)
    {
        $data = [];
        $uploadHandler = $this->getHeader($handlerName, $request->get('filesystem', ''));
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $files = current($request->files->all());
        $files = is_array($files) ? $files : [$files];
        foreach ($files as $file) {
            $data[] = $uploadHandler->upload($file)->toArray();
        }

        return new JsonResponse(['files' => $data]);
    }

    public function deleteAction(Request $request, $key, $handlerName)
    {
        $data = [];
        $data[$key] = $this->getHeader($handlerName, $request->get('filesystem', ''))->delete($key);

        return new JsonResponse(['files' => $data]);
    }

    /**
     * @param string $handlerName
     *
     * @return \FDevs\FileBundle\Handler\UploadHandler
     */
    private function getHeader($handlerName = 'file', $filesystemName = '')
    {
        /** @var \FDevs\FileBundle\Handler\UploadHandler $handler */
        $handler = $this->container->get('f_devs_file.handler.'.$handlerName);
        $filesystems = $this->container->getParameter('f_devs_file.filesystems');
        if ($filesystemName && isset($filesystems[$filesystemName])) {
            $handler->setConfig($filesystems[$filesystemName]);
        }

        return $handler;
    }
}
