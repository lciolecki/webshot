<?php

use Extlib\Http\Response;

/**
 * CutyCapt logic
 * 
 * @category    Webshot
 * @package     Logic
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 * @copyright   Lukasz Ciolecki (mart)
 */
class Webshot_Logic_CutyCapt extends Webshot_Logic_Abstract
{
    /**
     * Default extension file
     */
    const BASE_EXTENSION = 'png';

    /**
     * Avaiable CutyCapt options
     * 
     * @var array
     */
    protected $options = array();
    
    /**
     * Arra of default options
     * 
     * @var array 
     */
    protected $defaults = array();
    
    /**
     * Instancja konstruktora
     */
    public function __construct()
    {
        $this->options = $this->getConfig()->system->cutycapt->options->toArray();
        $this->defaults = $this->getConfig()->system->cutycapt->defaults->toArray();
        parent::__construct();
    }
    
    /**
     * Check count of avaiable screenshot for service
     * 
     * @param Entity\Service $service
     * @return boolean
     */
    public function checkService(Entity\Service $service)
    {
        if (null === $service->getWebshotsPerDay()) {
            return true;
        }
        
        $cnt = $this->getEm()->getRepository('Entity\Webshot')->getCountForDay($service->getId(), $this->getSystem()->getDate());
        if ($service->getWebshotsPerDay() <= $cnt) {
            return false;
        }
        
        return true;
    }


    /**
     * Create screenshot 
     * 
     * @param Entity\Service $service
     * @param array $params
     * @return \Entity\Webshot
     */
    public function createScreen(Entity\Service $service, array $data)
    {
        if (false === $this->checkSignKey($data, $service->getSecretKey())) {
             throw new Extlib\Exception(translate('Invalid key signature requests.'), Response::CODE_UNAUTHORIZED);
        }
        
        if (false === $this->checkService($service)) {
            throw new Extlib\Exception(sprintf(translate("Service '% s' has exhausted the limit - '% s' discharges on the day.", Response::CODE_NOT_ACCEPTABLE), 
                $service->getName(), 
                $service->getWebshotsPerDay()),
                Extlib\Http\Response::CODE_FORBIDDEN
            );
        }

        $output = $this->getOutputData();
        $params = $this->prepareParams($data);        
        $params['out'] = $output->filePath;

        $return = 0;
        $out = array();
        $command = sprintf("%s %s %s %s", 
            $this->config->system->xvfb->path,
            $this->getXvfbConfig(),
            $this->config->system->cutycapt->path, 
            $this->getParamsAsString($params)
        ); //>/home/mart/logs/webshot.log 2>&1
        
        $this->log($command);
        
        $this->getEm()->beginTransaction();
        try {
            $exec = exec($command, $out, $return);
            if (0 !== $return || false === file_exists($output->filePath)) {
                throw new Extlib\Exception(translate("An error occurred while performing command: '%s'."), $command);
            }
            
            $file = new \Entity\File();
            $file->setFilePath($output->relativePath);
            $this->getEm()->persist($file);
            
            $webshot = new \Entity\Webshot();
            $webshot->setUrl($params['url']);
            $webshot->setImage($file);
            $this->getEm()->persist($webshot);
            
            $service->addWebshot($webshot);
            
            $this->getEm()->flush();
            $this->getEm()->commit();
        } catch (\Exception $exc) {
            $this->getEm()->rollback();
            Extlib\FileManager::removeFile($output->filePath);
            throw new Extlib\Exception(translate('An error occurred while creating the dump.'), Response::CODE_INTERNAL_SERVER_ERROR, $exc);
        } 

        return $webshot;
    }
    
    /**
     * Get output data for screena
     * 
     * @return \stdClass
     */
    public function getOutputData($extension = self::BASE_EXTENSION)
    {
        $output = new stdClass();
        $output->storage = rtrim($this->config->system->storage, DIRECTORY_SEPARATOR);
        $output->webshosDir = trim($this->config->system->dir->webshots, DIRECTORY_SEPARATOR);
        $output->dateDir = \Extlib\FileManager::getDateDirectory();
        $output->relativeDir = $output->webshosDir . DIRECTORY_SEPARATOR . $output->dateDir;
        $output->directory = \Extlib\FileManager::dir($output->storage . DIRECTORY_SEPARATOR . $output->relativeDir);
        $output->filename = \Extlib\FileManager::generateFileName($output->directory, $extension);
        $output->filePath = $output->directory . DIRECTORY_SEPARATOR . $output->filename;
        $output->relativePath = $output->relativeDir . DIRECTORY_SEPARATOR . $output->filename;
        
        return $output;
    }  
    
    /**
     * Get xvfb config
     * 
     * @return string
     */
    public function getXvfbConfig()
    {
        $xvfb = '';
        foreach ($this->getConfig()->system->xvfb->options as $name => $option) {
            if (is_numeric($name)) {
                $xvfb .= sprintf(' --%s', $option);
            } else {
                $xvfb .= sprintf(' --%s="%s"', $name, $option); 
            }
        }
        
        return $xvfb;
    }


    /**
     * Parsinge params
     * 
     * @param array $params
     * @return string
     */
    public function getParamsAsString(array $params)
    {
        $string = '';
        foreach ($params as $name => $param){
            $string .= sprintf(' --%s=%s', $name, $param);
        }
        
        return $string;
    }
    
    /**
     * Prepare params 
     * 
     * @param array $params
     * @return array
     */
    public function prepareParams(array $params)
    {
        $return = array();
        foreach ($params as $param => $value) {
            if (in_array($param, $this->options)) {
                $return[$param]= $value;
            }
        }
        
        foreach ($this->defaults as $default => $value) {
            if (false === in_array($default, $return)) {
                $return[$default]= $value;
            }
        }
  
        if (false === isset($return['url'])) {
            throw new Extlib\Exception(\translate('No url.'), Response::CODE_NOT_ACCEPTABLE);
        }
        
        $return['url'] = \Extlib\Utils::getInstance()->filterUrl($return['url']);
        
        $urlValidator = new \Extlib\Validate\Url();
        if (false === $urlValidator->isValid($return['url'])) {
            throw new Extlib\Exception(sprintf(\translate("Url: '% s' is invalid."), $return['url']), Response::CODE_NOT_ACCEPTABLE);
        }
        
        return $return;
    }
}