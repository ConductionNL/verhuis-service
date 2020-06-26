<?php
namespace App\Service;

use App\Entity\WebHook;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class VerhuisService
{
    private $em;
    private $commonGroundService;

    public function __construct(EntityManagerInterface $em, CommonGroundService $commonGroundService)
    {
        $this->em = $em;
        $this->commonGroundService = $commonGroundService;
    }

    public function webHook($task, $resource){


        switch($task['code']) {
            case "set_bsn":
                $resource = $this->setBsn($task, $resource);
                break;
            default:
               break;
        }

        // dit pas live gooide nadat we in de event hook optioneel hebben gemaakt
        $this->commonGroundService->saveResource($resource);
    }



    public function setBsn(array $task, array $resource)
    {
        $wiebsn = [];
        if(array_key_exists('wie',$resource['properties']) && !is_array($resource['properties']['wie'])){
            $wie = str_replace(["[","}"],"",$resource['properties']['wie']);
            $wie = explode(",", $wie);
            foreach ($wie as $brpurl){
                $wiebsn[] = $this->commonGroundService->getUuidFromUrl($brpurl);
            }
            $resource['properties']['wie'] = $wie;
            $resource['properties']['wiebsn'] = $wiebsn;
        }
        
        return $resource;
    }
}
