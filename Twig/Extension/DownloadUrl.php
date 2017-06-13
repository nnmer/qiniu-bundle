<?php

namespace Nnmer\QiniuBundle\Twig\Extension;

use Nnmer\QiniuBundle\Service\QiniuService;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DownloadUrl extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'nnmer_qiniu_download_url';
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('nnmer_qiniu_download_url', array($this, 'downloadUrl')),
        );
    }

    /**
     *
     */
    public function downloadUrl($url, $serviceName)
    {
        if ($this->container->has($serviceName)) {
            /** @var QiniuService $qiniuService */
            $qiniuService = $this->container->get($serviceName);
            return $qiniuService->getAuth()->privateDownloadUrl($url);
        }else{
            return $url;
        }
    }
}