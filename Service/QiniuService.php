<?php

namespace Nnmer\QiniuBundle\Service;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Zone;

class QiniuService
{
    /** @var  Auth */
    private $auth;

    /** @var  UploadManager */
    private $uploadManager;

    /** @var  string */
    private $bucket;

    public function __construct($accessKey, $secretKey, $bucket)
    {
        $this->auth     = new Auth($accessKey, $secretKey);
        $this->bucket   = $bucket;

        $this->uploadManager  = new UploadManager();
    }

    public function getUploadManager()
    {
        return $this->uploadManager;
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

    public function isQiniuCallback(){
        $authstr = $_SERVER['HTTP_AUTHORIZATION'];
        if(strpos($authstr,"QBox ")!=0){
            return false;
        }
        $auth = explode(":",substr($authstr,5));
        if(sizeof($auth)!=2||$auth[0]!=C('accessKey')){
            return false;
        }
        $data = "/callback.php\n".file_get_contents('php://input');
        return URLSafeBase64Encode(hash_hmac('sha1',$data,C("secretKey"), true)) == $auth[1];
    }
}