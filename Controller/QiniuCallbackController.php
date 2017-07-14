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
        header('Content-Type: application/json');
        http_response_code(200);
        fastcgi_finish_request();

        $this->get('event_dispatcher')->dispatch(QiniuEvents::FILE_UPLOADED, new QiniuUploaderEvent($payload));
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route(name="nnmer_qiniu_persistence_notify_url", path="/qiniu-persistence-notify-url", methods={"POST"})
     */
    public function persistenceNotifyUrlAction(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        if (null === $payload){
            throw new \LogicException('Payload received from Qiniu is not a json content');
        }
        header('Content-Type: application/json');
        http_response_code(200);
        fastcgi_finish_request();

        $this->get('event_dispatcher')->dispatch(QiniuEvents::PERSISTENCE_RESULTS_RECEIVED, new QiniuPersistenceEvent($payload));
        return new JsonResponse();
    }
}