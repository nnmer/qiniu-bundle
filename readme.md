### Installation

1. At AppKernel.php add bundle:
```php
 new Nnmer\QiniuBundle(),
```
2. Add routing
```yaml
nnmer_qiniu:
    resource: "@NnmerQiniuBundle/Resources/config/routing.yml"
    prefix:   /qiniu

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