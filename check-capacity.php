<?php
// check-capacity.php - Uses OCI CLI (always available in GitHub Actions)
echo "Testing OCI CLI connection...\n";

$compartmentId = getenv('OCI_COMPARTMENT_ID');
if (!$compartmentId) {
    echo "❌ OCI_COMPARTMENT_ID missing\n";
    exit(1);
}

$cmd = sprintf(
    'oci compute shape list --compartment-id %s --availability-domain "gAQo:AP-MUMBAI-1-AD-1" --shape-name "VM.Standard.A1.Flex" --query "length(data)" --raw-output 2>/dev/null',
    escapeshellarg($compartmentId)
);

$output = shell_exec($cmd);
$shapeCount = trim($output ?? '0');

echo "A1.Flex shapes found: $shapeCount\n";

if ($shapeCount > 0) {
    echo "✅ A1.Flex capacity available!\n";
    exit(0);
} else {
    echo "❌ No A1.Flex capacity\n";
    exit(1);
}
?>
