<?php
// check-capacity.php - Tests ap-mumbai-1 A1.Flex 4/24 availability
require 'vendor/autoload.php';

use OCI\Compute\{ComputeClient, Models\ListShapesRequest};
use OCI\Config;

$config = new Config();
$compute = new ComputeClient($config);

try {
    // Test ap-mumbai-1 AD-1 for A1.Flex with 24GB+ support
    $listShapesRequest = new ListShapesRequest();
    $listShapesRequest->setCompartmentId($_ENV['OCI_COMPARTMENT_ID']);
    $listShapesRequest->setAvailabilityDomain('gAQo:AP-MUMBAI-1-AD-1');
    $listShapesRequest->setShapeName('VM.Standard.A1.Flex');
    
    $response = $compute->listShapes($listShapesRequest);
    
    foreach ($response->getItems() as $shape) {
        $ocpuOptions = $shape->getOcpuOptions();
        if ($ocpuOptions->getMaxMemoryInGBs() >= 24) {
            exit(0);  // ✅ Capacity available
        }
    }
    exit(1);  // ❌ No 4/24 capacity
} catch (Exception $e) {
    exit(1);  // ❌ API error or no capacity
}
?>
