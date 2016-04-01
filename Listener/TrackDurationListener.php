<?php

namespace Gamma\Framework\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class TrackDurationListener
{
    protected $stopwatchLogger;
    private $uri;
    private $requestApiContent;
    private $requestMethod;

    public function __construct($stopwatchLogger)
    {
        $this->stopwatchLogger = $stopwatchLogger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $this->uri   = $request->getRequestUri();

        if (!preg_match('/\/api/', $this->uri)) {
            return;
        }

        $this->stopwatchLogger->start($this->uri);
        $this->requestMethod = $request->getMethod();
        $receivedRawData = $request->getContent();

        if($receivedRawData) {
            $parsedData = json_decode($receivedRawData, true);
            $this->requestApiContent = $parsedData;
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !preg_match('/\/api/', $this->uri)) {
            return;
        }

        $params = array('method' => $this->requestMethod);
        $response  = $event->getResponse();

        if($this->requestApiContent) {
            $params['request'] = $this->requestApiContent;
        }

        if(preg_match('/\/api/', $this->uri)) {
            $params['response'] = json_decode($response->getContent(), true);
        }

        $this->stopwatchLogger->stop($this->uri, $params);
    }
}
