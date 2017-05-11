<?php

namespace Nnmer\QiniuBundle\DependencyInjection;

use Nnmer\QiniuBundle\Service\QiniuService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NnmerQiniuExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $config = $this->createBucketServices($container, $config);


        // define some parameters
        $container->setParameter('nnmer_qiniu.buckets', $config['buckets']);

        if (!in_array($config['defaultBucket'],$config['buckets'])){
            throw new \RuntimeException(sprintf('The default bucket "%s" should be one of next: %s', $config['defaultBucket'], join(', ', $config['buckets'])));
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function createBucketServices(ContainerBuilder $container, array $config)
    {
        foreach ($config['buckets'] as $bucket) {
            $config['bucket_services'][$bucket] = $this->createBucketService($container, $config['accessKey'], $config['secretKey'], $bucket);
        }

        $container->setAlias('nnmer_qiniu.service', sprintf('nnmer_qiniu.%s_service', $config['defaultBucket']));

        return $config;
    }

    protected function createBucketService(ContainerBuilder $container, $accessKey, $secretKey, $bucket)
    {
        $serviceId  = sprintf('nnmer_qiniu.%s_service', $bucket);
        $definition = $container->setDefinition(
            $serviceId, new Definition(QiniuService::class, [$accessKey, $secretKey, $bucket])
        );

        return $serviceId;
    }
}
