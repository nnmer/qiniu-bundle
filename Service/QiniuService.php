<?php

namespace Nnmer\QiniuBundle\Service;

use Qiniu\Auth;
use Qiniu\Zone;

class QiniuService
{
    /** @var  Auth */
    private $auth;

    /** @var  string */
    private $bucket;

    public function __construct($accessKey, $secretKey, $bucket)
    {
        $this->auth     = new Auth($accessKey, $secretKey);
        $this->bucket   = $bucket;
    }

    /**
     * @param null|string $key
     * @param int $expires
     * @param null|array $policy
     * @param bool $strictPolicy
     * @param Zone|null $zone
     * @return string
     */
    public function getUploadToken($key = null,
                                   $expires = 3600,
                                   $policy = null,
                                   $strictPolicy = true,
                                   Zone $zone = null)
    {
        return $token = $this->auth->uploadToken($this->bucket, $key, $expires, $policy, $strictPolicy, $zone);
    }
}