<?php
require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Dataset\CsvDataset;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Metric\Accuracy;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\ModelManager;
use Phpml\Metric\ClassificationReport;

//$dataset = new CsvDataset( 'data/feed.csv', 1, true );
$dataset = new CsvDataset( 'data/train_data.csv', 1, true );

//var_dump( $dataset ); die();
//$dataset = new CsvDataset( 'data/language.csv', 1, true );
$vectorizer = new TokenCountVectorizer( new WordTokenizer() );
$tfIdfTransformer = new TfIdfTransformer();

$samples = [];
foreach ( $dataset->getSamples() as $sample ) {
    $samples[] = $sample[0];
}

$vectorizer->fit( $samples );
$vectorizer->transform( $samples );

$tfIdfTransformer->fit( $samples );
$tfIdfTransformer->transform( $samples );



$dataset = new ArrayDataset( $samples, $dataset->getTargets() );

$randomSplit = new StratifiedRandomSplit( $dataset, 0.2, 63452 );

$classifier = new SVC( Kernel::RBF, 10000 );
$classifier->train( $randomSplit->getTrainSamples(), $randomSplit->getTrainLabels() );

$filepath = 'memory/model';
$modelManager = new ModelManager();
$modelManager->saveToFile( $classifier, $filepath );

$restoredClassifier = $modelManager->restoreFromFile( $filepath );
$predictSamples = $randomSplit->getTestSamples();
$predictedLabels = $restoredClassifier->predict( $predictSamples );

for ( $i = 0; $i < sizeof( $predictedLabels ); $i++ ) {
    echo 'Predicted Label: ' . $predictedLabels[$i] . ' -> Expected: ' . $randomSplit->getTestLabels()[$i] . PHP_EOL;
}

$report = new ClassificationReport( $randomSplit->getTestLabels(), $predictedLabels );

echo 'Accuracy: ' . Accuracy::score( $randomSplit->getTestLabels(), $predictedLabels ) . PHP_EOL;

foreach ( $report->getPrecision() as $label => $precision ) {
    echo 'Label: ' . $label . ' -- ' . $precision . PHP_EOL;
}
