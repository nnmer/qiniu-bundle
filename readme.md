This bundle aim is to help to work with [七牛](https://www.qiniu.com/) CDN service.

The work on bundle is in process, the documentation will be updated respectively.

### Installation

1. 

`composer require nnmer/qiniu-bundle`

2. At AppKernel.php add bundle:
```php
 new Nnmer\QiniuBundle(),
```

3. config.yml
```yaml
nnmer_qiniu:
    defaultBucket: a1
    initiateAdapters:
        - gaufrette
    buckets: # is an array of the buckets, later each of them will be available as a service. Should be at least 1 provided
        - a1
        - b2
```



### Available services

NOTE: bucket's name in services being renamed from original name:
all `-` arereplaces by `_`

After container is built available next services:

`nnmer_qiniu.*_service` , where `*` is each of the `buckets`

and `nnmer_qiniu.service` which is alias to the service with `defaultBucket`

if KnpGaufretteBundle is installed and `nnmer_qiniu.initiateAdapters` has `gaufrette` as array element then additional 
next services will be generate:
 
`nnmer_qiniu.gaufrette_*_adapter` where `*` is each of the `buckets`