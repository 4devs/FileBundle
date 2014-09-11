Getting Started With FileBundle
===========================================

## Installation and usage

Installation and usage is a quick:

1. Download FileBundle using composer
2. Enable the Bundle
3. Use the bundle


### Step 1: Download FileBundle using composer

Add FileBundle in your composer.json:

```js
{
    "require": {
        "fdevs/file-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update fdevs/file-bundle
```

Composer will install the bundle to your project's `vendor/fdevs` directory.


### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FDevs\FileBundle\FDevsFileBundle(),
        new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
    );
}
```

#### add javascripts on page

``` twig
{{ include 'FDevsFileBundle::javascripts.html.twig' }}
```
or

``` twig
<script src="{{ asset('bundles/fdevsfile/js/vendor/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('bundles/fdevsfile/js/jquery.iframe-transport.js') }}"></script>
<script src="{{ asset('bundles/fdevsfile/js/jquery.fileupload.js') }}"></script>
<script src="{{ asset('bundles/fdevsfile/js/fileUpload.js') }}"></script>
```

#### add stylesheets on page


``` twig
{{ include 'FDevsFileBundle::stylesheets.html.twig' }}
```
or

``` twig
<link rel="stylesheet" href="{{ asset('bundles/fdevsfile/css/jquery.fileupload.css') }}">
```

#### add route

``` yaml
# app/config/routing.yml
f_devs_file:
    resource: "@FDevsFileBundle/Resources/config/routing.xml"
    prefix:   /file
```

#### add config

``` yaml
# app/config/config.yml
f_devs_file:
    default: 'local'
    filesystems:
        local:
            web_path: "http://%domain%/uploads/image/"
            validation_options:
                accept_file_types: '/(\.|\/)(gif|jpe?g|png)$/i'
            thumbs:
                small: {height: 100, width: 100}
            gaufrette_filesystem: 'base'
        team:
            web_path: "http://%domain%/uploads/team/"
            validation_options:
                accept_file_types: '/(\.|\/)(gif|jpe?g|png)$/i'
            thumbs:
                small: {height: 225, width: 225}
            gaufrette_filesystem: 'team'
        catalog:
            web_path: "http://%domain%/uploads/catalog/"
            validation_options:
                accept_file_types: '/(\.|\/)(gif|jpe?g|png)$/i'
            thumbs:
                small: {height: 289, width: 400}
                prewiew: {height: 450, width: 600, type: 'crop'}
            gaufrette_filesystem: 'catalog'

knp_gaufrette:
    adapters:
        base:
            local:
                directory: "%kernel.root_dir%/../web/uploads/image/"
        team:
            local:
                directory: "%kernel.root_dir%/../web/uploads/team/"
        catalog:
            local:
                directory: "%kernel.root_dir%/../web/uploads/catalog/"
    filesystems:
        base:
            adapter: base
        team:
            adapter: team
        catalog:
            adapter: catalog
```


3. Use the bundle


add field string in your Model
``` php
class Item
{

    /**
     * @var string $image
     */
    protected $image;

```

use in form

``` php
$builder->add('image', 'fdevs_file');
$builder->add('image', 'fdevs_image', ['filesystem' => 'catalog', 'validation_options' => ['max_file_size' => 1000]]);
```
