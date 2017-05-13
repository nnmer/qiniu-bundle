<?php
namespace Nnmer\QiniuBundle\Controller;


use Nnmer\QiniuBundle\Events\QiniuEvents;
use Nnmer\QiniuBundle\Events\QiniuPersistenceEvent;
use Nnmer\QiniuBundle\Events\QiniuUploaderEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QiniuCallbackController extends Controller implements QiniuCallbackControllerInterface
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route(name="nnmer_qiniu_callback_url", path="/qiniu-callback-url", methods={"POST"})
     */
    public function callbackUrlAction(Request $request)
    {
        parse_str($request->getContent(),$payload);
        $this->get('event_dispatcher')->dispatch(QiniuEvents::FILE_UPLOADED, new QiniuUploaderEvent($payload));

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route(name="nnmer_qiniu_persistence_notify_url", path="/qiniu-persistence-notify-url", methods={"POST"})
     */
    public function persistentNotifyUrlAction(Request $request)
    {
        parse_str($request->getContent(),$payload);
        $this->get('event_dispatcher')->dispatch(QiniuEvents::PERSISTENCE_RESULTS_RECEIVED, new QiniuPersistenceEvent($payload));

        return new JsonResponse();
    }
}