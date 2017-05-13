<?php
namespace Nnmer\QiniuBundle\Service;


use Gaufrette\Adapter;

class GaufretteQiniuAdapter implements Adapter
{
    /** @var  QiniuService */
    protected $qiniuService;

    /** @var  string */
    protected $bucket;

    public function __construct($bucket, QiniuService $qiniuService)
    {
        $this->bucket       = $bucket;
        $this->qiniuService = $qiniuService;
    }

    /**
     * Reads the content of the file
     *
     * @param string $key
     *
     * @return string|boolean if cannot read content
     */
    public function read($key)
    {
        // TODO: Implement read() method.
    }

    /**
     * @inheritdoc
     */
    public function write($key, $content)
    {
        $return = $this->qiniuService->getUploadManager()->put($this->qiniuService->getUploadToken(),$key, $content);
        if ($return[0]===null){
            throw new \Exception('Cannot upload file to Qiniu by GaufretteQiniuAdapter');
        }
    }

    /**
     * Indicates whether the file exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public function exists($key)
    {
        $return = $this->qiniuService->getBucketManager()->stat($this->bucket, $key);
        if ($return[0]===null){
            return false;
        }

        return true;
    }

    /**
     * Returns an array of all keys (files and directories)
     *
     * @return array
     */
    public function keys()
    {
        // TODO: Implement keys() method.
    }

    /**
     * Returns the last modified time
     *
     * @param string $key
     *
     * @return integer|boolean An UNIX like timestamp or false
     */
    public function mtime($key)
    {
        // TODO: Implement mtime() method.
    }

    /**
     * Deletes the file
     *
     * @param string $key
     *
     * @return boolean
     */
    public function delete($key)
    {
        $return = $this->qiniuService->getBucketManager()->delete($this->bucket, $key);
        if (null !== $return){
            return false;
        }

        return true;
    }

    /**
     * Renames a file
     *
     * @param string $sourceKey
     * @param string $targetKey
     *
     * @return boolean
     */
    public function rename($sourceKey, $targetKey)
    {
        // TODO: Implement rename() method.
    }

    /**
     * Check if key is directory
     *
     * @param string $key
     *
     * @return boolean
     */
    public function isDirectory($key)
    {
        // TODO: Implement isDirectory() method.
    }
}