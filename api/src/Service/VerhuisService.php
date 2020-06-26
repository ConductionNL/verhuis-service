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
        // We want to force product shizle
        $ceremonieOfferId = $this->commonGroundService->getUuidFromUrl($resource['properties']['plechtigheid']);
        switch ($ceremonieOfferId) {
            case "1ba1772b-cc8a-4808-ad1e-f9b3c93bdebf": // Flits huwelijks
                $resource['properties']['ambtenaar'] = $this->commonGroundService->cleanUrl(['component' => 'pdc', 'type' => 'offers', 'id' => '55af09c8-361b-418a-af87-df8f8827984b']);
                $resource['properties']['locatie'] = $this->commonGroundService->cleanUrl(['component' => 'pdc', 'type' => 'offers', 'id' => '9aef22c4-0c35-4615-ab0e-251585442b55']);
                break;
            case "77f6419d-b264-4898-8229-9916d9deccee": // Gratis trouwen
                $resource['properties']['ambtenaar'] = $this->commonGroundService->cleanUrl(['component' => 'pdc', 'type' => 'offers', 'id' => '55af09c8-361b-418a-af87-df8f8827984b']);
                $resource['properties']['locatie'] = $this->commonGroundService->cleanUrl(['component' => 'pdc', 'type' => 'offers', 'id' => '7a3489d5-2d2c-454b-91c9-caff4fed897f']);
                break;
            case "2b9ba0a9-376d-45e2-aa83-809ef07fa104": // Eenvoudig trouwen
                $resource['properties']['ambtenaar'] = $this->commonGroundService->cleanUrl(['component' => 'pdc', 'type' => 'offers', 'id' => '55af09c8-361b-418a-af87-df8f8827984b']);
                $resource['properties']['locatie'] = $this->commonGroundService->cleanUrl(['component' => 'pdc', 'type' => 'offers', 'id' => '7a3489d5-2d2c-454b-91c9-caff4fed897f']);
                break;
            case "bfeb9399-fce6-49b8-a047-70928f3611fb": // Uitgebreid trouwen
                // In het geval van uitgebreid trouwen hoeven we niks te forceren
                break;
        }
    }
}
