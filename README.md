Webshot screen server application 
=======

Webshot Screen Server it's an web application for generate website screenshots. System is based on CutyCapt application (http://cutycapt.sourceforge.net). It's allows you to take screenshots of any web pages and save them as images in png formats. Application was created in PHP technology and uses Zend Framework whith Doctrine 2.

Required: xvfb-run and cutycapt applications. 

Avaiable options: url,min-width,min-height,max-wait,delay,user-style-path,user-style-string,header,method,body-string,body-base64,app-name,app-version,user-agent,app,javascript,java,plugins,private-browsing,auto-load-images,js-can-open-windows,js-can-access-clipboard,print-backgrounds,zoom-factor,zoom-text-only,http-proxy.


##Installation using Composer

    {
        "require": {
            "lciolecki/webshot": "dev-master"
        }
    }
    
**Important**: You must do composer install -o
    
#Sample use in Zend Framework

    public function webshotAction()
    {
            $params = array(
              'url' => 'http://google.pl',
              'hash' => '2131sada', //unique identifcator of service
              'sign' => 'adad13123' //sign key for http://google.pl + 2131sada
            );
            
            $url = 'yourdomain/api/create';            
            $client = new Zend_Http_Client($url);
            $client->setMethod(Zend_Http_Client::POST)
                   ->setHeaders('X-Requested-With', 'XMLHttpRequest') 
                   ->setParameterPost($params)
                   ->setConfig(array('timeout' => 180));
            
            $response = $client->request();
            
            $return = Zend_Json::decode($response->getBody());
            $code = isset($return['code']) ? $return['code'] : 200;
        
          if ($code === 200) {
              $this->_helper->viewRenderer->setNoRender(true);
              $this->_helper->layout->disableLayout();
              
              $content = file_get_contents('http://webshot.loc/system/image/hash/22b46f4bca9fc167e041e9a15ab46f19');
              $this->getResponse()->setHeader('Content-type', 'image/png');
              $this->getResponse()->setBody($content);
              $this->getResponse()->sendResponse();  
            } else {
              throw new Exception('An error on generate screenshot');
            }
        }
    
