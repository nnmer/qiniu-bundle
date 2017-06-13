This bundle aim is to help to work with [七牛](https://www.qiniu.com/) CDN service.

The work on bundle is in process, the documentation will be updated respectively.

### Installation & Configuration

_Note: this bundle doesn't provide any 七牛 frontend related code._ 

1. Install: `composer require nnmer/qiniu-bundle`

2. Add bundle AppKernel.php :
```php
 ...
 new Nnmer\QiniuBundle(),
 ...
```

3. Add bundle configuration at config.yml
```yaml
nnmer_qiniu:
    defaultBucket: a1
    initiateAdapters:
        - gaufrette
    buckets: # is an array of the buckets, later each of them will be available as a service. Should be at least 1 provided
        - a1
        - b2
```

4. Add to you routing
```yaml
qiniu_processing_results:
    resource: "@NnmerQiniuBundle/Controller/QiniuCallbackController.php"
    type:     annotation
    prefix:   /
```

5. if you are behind symfony's firewall, then add to your security.yml  access_control section:
```yaml
- { path: ^/qiniu-callback-url, role: IS_AUTHENTICATED_ANONYMOUSLY }
- { path: ^/qiniu-persistence-notify-url, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

6. Done. From this point once you will receive a callback from Qiniu the 2 events can be raised, depends on the callback:
- `QiniuEvents::FILE_UPLOADED`
- `QiniuEvents::PERSISTENCE_RESULTS_RECEIVED`

the content of the event is the payload of the callback, so you can process you logic by listening to this events and manage the payload data

### Overwriting controller

If you want to have custom controller logic then do either:
- extend `QiniuCallbackController` class (this will give you existing 2 callbacks defined)
- implement `QiniuCallbackControllerInterface` interface (you will need to define the content for the controller's 2 callbacks methods)

In this case remember to repoint routing definition to your content, or remove it if you manage routing definitions yourself

### Available services

NOTE: bucket's name in services being renamed from original name:
all `-` arereplaces by `_`

After container is built available next services:

`nnmer_qiniu.*_service` , where `*` is each of the `buckets`

and `nnmer_qiniu.service` which is alias to the service with `defaultBucket`

if KnpGaufretteBundle is installed and `nnmer_qiniu.initiateAdapters` has `gaufrette` as array element then additional 
next services will be generate:
 
`nnmer_qiniu.gaufrette_*_adapter` where `*` is each of the `buckets`

### Twig helpers
`downloadUrl(url, service)` - to build a download link, signed and with time expiration. `service` is the id of the needed service.
(in a controller the alias is $this->container->get('service id here')->getAuth()->privateDownloadUrl($url))