<?php
require './vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Credentials\CredentialProvider;
use Aws\Exception\AwsException;

$waitTimeInSeconds = $_SERVER['WAIT_TIME_IN_SECONDS'] ?? null;
if (null === $waitTimeInSeconds) {
    throw new \Exception('Missing WAIT_TIME_IN_SECONDS env var.');
}
echo sprintf('WAIT_TIME_IN_SECONDS env var: %s', $waitTimeInSeconds) . PHP_EOL;
$waitTimeInSeconds = (int) $waitTimeInSeconds;

echo "Using AWS SDK for PHP version " . Aws\Sdk::VERSION . PHP_EOL;

$provider = CredentialProvider::assumeRoleWithWebIdentityCredentialProvider();

$count = 0;
$startDate = time();

while (true) {
    //Create a S3Client

    $s3Client = new S3Client([
        //'profile' => 'default',
        'provider' => $provider,
        'region' => 'eu-west-1',
        'version' => '2006-03-01'
    ]);
    $date = date('Y-m-d H:i:s');
     
    //Listing all S3 Bucket
   try {
        $buckets = $s3Client->listBuckets();
        foreach ($buckets['Buckets'] as $bucket) {
          echo sprintf(' %s%s', $bucket['Name'], PHP_EOL);
        }
        echo "Found " . sizeof($buckets['Buckets']) . " buckets." . PHP_EOL;
    } catch (AwsException $e) {
        echo "Error listing buckets: {$e->getMessage()}" . PHP_EOL;
    }
    $count++;
    $runMins = (time() - $startDate) / 60;
    echo "###" . PHP_EOL . "Token retrieved {$count} times" . PHP_EOL;
    echo "Script has been running for {$runMins} min" . PHP_EOL . "###" . PHP_EOL;
    echo sprintf('%s Sleeping %s seconds%s', $date, $waitTimeInSeconds, PHP_EOL);
    sleep($waitTimeInSeconds);
}
