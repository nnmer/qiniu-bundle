<?php

namespace Nnmer\QiniuBundle\Service;

use Qiniu\Auth;
use function Qiniu\base64_urlSafeEncode;
use Qiniu\Http\Error;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Qiniu\Zone;

class QiniuService
{
    /** @var  Auth */
    private $auth;

    /** @var  UploadManager */
    private $uploadManager;

    /** @var BucketManager  */
    private $bucketManager;

    /** @var Zone  */
    private $zone;

    /** @var  string */
    private $bucket;

    public function __construct($accessKey, $secretKey, $bucket)
    {
        $this->auth     = new Auth($accessKey, $secretKey);
        $this->bucket   = $bucket;

        $this->uploadManager  = new UploadManager();
        $this->zone           = new Zone();
        $this->bucketManager  = new BucketManager($this->auth, $this->zone);
    }

    /**
     * @return UploadManager
     */
    public function getUploadManager()
    {
        return $this->uploadManager;
    }

    /**
     * @return BucketManager
     */
    public function getBucketManager()
    {
        return $this->bucketManager;
    }

    /**
     * @return Zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @return string
     */
    public function getBucketName()
    {
        return $this->bucket;
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

    /**
     * Prepare parameters for avthumb processing
     *
     * @param array $avthumbConfig
     * @param null $saveAs              - string should be in a format "bucket:key"
     * @return bool|string
     */
    public function prepareAvthumbParameters($avthumbConfig = [], $saveAs = null)
    {
        if (empty($avthumbConfig)){
            return false;
        }

        if (is_array($avthumbConfig)) {
            $fops = join('/',
                array_map(
                    function ($key, $item) { return $key . '/' . $item;},
                    array_keys($avthumbConfig),
                    $avthumbConfig)
            );
        }else{
            $fops = $avthumbConfig;
        }


        if (null !== $saveAs && is_string($saveAs)){
            $fops .= "|saveas/".base64_urlSafeEncode($saveAs);
        }

        return $fops;
    }


    /**
     * Prepare parameters for avconcat processing
     *
     * @param int $mode
     * @param $format
     * @param mixed $concatUrls
     * @param null $saveAs
     * @return bool|string
     */
    public function prepareAvconcatParameters($mode = 2, $format, $concatUrls, $saveAs = null)
    {
        if (empty($concatUrls)){
            return false;
        }
        $fops = 'avconcat/'.$mode.'/format/'.$format.'/';

        if (is_array($concatUrls)) {
            $fops .= join('/',
                array_map(
                    function ($item) { return base64_urlSafeEncode($item);},
                    $concatUrls)
            );
        }else{
            $fops .= base64_urlSafeEncode($concatUrls);
        }


        if (null !== $saveAs && is_string($saveAs)){
            $fops .= "|saveas/".base64_urlSafeEncode($saveAs);
        }

        return $fops;
    }

    /**
     *
     * Submit fops execution to Qiniu
     *
     * @param string        $key
     * @param array|string  $fopConfig
     * @param string|null   $pipeline
     * @param string|null   $notifyUrl
     * @param bool          $force
     * @return string
     *
     * @throws \LogicException
     */
    public function executePersistenceProcess($key, $fopConfig, $pipeline=null, $notifyUrl=null, $force = false)
    {
        $fops = new PersistentFop($this->auth, $this->bucket, $pipeline, $notifyUrl, $force);
        $result = $fops->execute($key, $fopConfig);
        if (null !== $result[0]){
            return $result[0];      // success, return persistentId
        }elseif ($result[1] instanceof Error){
            /** @var Error $error */
            $error = $result[1];
            throw new \LogicException($error->message(), $error->code());
        }else{
            throw new \LogicException('Cannot determine error from the Qiniu fops execution');
        }
    }


    // TODO: check this method, and correct it. Check the whether is QIniu callback at controller methods
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
        return base64_urlSafeEncode(hash_hmac('sha1',$data,C("secretKey"), true)) == $auth[1];
    }
}