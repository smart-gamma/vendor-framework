<?php

namespace Gamma\Framework\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class TrackDurationListener
{
    protected $stopwatch;
    private $uri;
    private $requestApiContent;
    private $requestMethod;
    private $responseApiContent;

    public function __construct($stopwatch) 
    {
        $this->stopwatch = $stopwatch;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        
        $request = $event->getRequest();
        $this->uri   = $request->getRequestUri();
        //echo 'start:'.$this->uri;
        $this->stopwatch->start($this->uri);
                         
        $this->requestMethod = $request->getMethod();

        $receivedRawData = $request->getContent();
        if($receivedRawData){
            $parsedData = json_decode($receivedRawData, true);
            $this->requestApiContent = $parsedData;
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        
        $params = array('method' => $this->requestMethod);
        $response  = $event->getResponse();
        
        if($this->requestApiContent){
            $params['request'] = $this->requestApiContent;
        }

        if(preg_match('/\/api/', $this->uri)){
            $params['response'] = $response->getContent();
        }

        //echo 'stop:'.$this->uri;
        $this->stopwatch->stop($this->uri, $params);
    }
}
