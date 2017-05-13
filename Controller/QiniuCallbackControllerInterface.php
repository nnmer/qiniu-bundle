<?php
namespace Nnmer\QiniuBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

interface QiniuCallbackControllerInterface
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route(name="nnmer_qiniu_callback_url", path="/qiniu-callback-url", methods={"POST"})
     */
    public function callbackUrlAction(Request $request);

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route(name="nnmer_qiniu_persistence_notify_url", path="/qiniu-persistent-notify-url", methods={"POST"})
     */
    public function persistenceNotifyUrlAction(Request $request);
}