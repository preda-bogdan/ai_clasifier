<?php
require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/app/class-database.php';

use Phpml\Dataset\CsvDataset;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Metric\Accuracy;
use Phpml\Classification\SVC;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\NaiveBayes;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\ModelManager;
use Phpml\Metric\ClassificationReport;


class Ai_Lala {

    private $train_file;
    private $model_file;

    private $ai;
    private $modelManager;
    private $vectorizer;
    private $tfIdfTransformer;

    private $randomSplit;

    private $dataSet;
    private $testDataSet;

    public function __construct( $autoload = true, $classifier = 0 ) {
        include ROOT . '/app/configs/config.php';
        $this->train_file = $train_file;
        $this->model_file = $memory;

	    $this->ai = new SVC( Kernel::RBF, 10000 );
        if ( $classifier == 1 ) {
	        $this->ai = new KNearestNeighbors();
        } else if ( $classifier == 2 ) {
	        $this->ai = new NaiveBayes();
	    }


	    $this->modelManager =  new ModelManager();

	    $this->vectorizer = new TokenCountVectorizer( new WordTokenizer() );
	    $this->tfIdfTransformer = new TfIdfTransformer();

	    if( file_exists( $this->model_file . '_vectorizer' ) && $autoload == true ) {
            $this->vectorizer = unserialize( file_get_contents( $this->model_file . '_vectorizer' ) );
        }

        if( file_exists( $this->model_file . '_tfIdfTransformer' ) && $autoload == true ) {
            $this->tfIdfTransformer = unserialize( file_get_contents( $this->model_file . '_tfIdfTransformer' ) );
        }

        if ( file_exists( $this->model_file ) && $autoload == true ) {
        	$this->load_ai();
        }
    }

    public function normalize( $samples ) {
	    $this->vectorizer->fit( $samples );
	    $this->vectorizer->transform( $samples );

	    $this->tfIdfTransformer->fit( $samples );
	    $this->tfIdfTransformer->transform( $samples );

	    file_put_contents( $this->model_file . '_vectorizer', serialize( $this->vectorizer ) );
	    file_put_contents( $this->model_file . '_tfIdfTransformer', serialize( $this->tfIdfTransformer ) );

	    return $samples;
    }

    public function build_data_set( $source, $is_file = false ) {
    	if ( ! $is_file ) {
		    $source['samples'] = $this->normalize( $source['samples'] );
		    $this->dataSet = new ArrayDataset( $source['samples'], $source['labels'] );
		    return $this->dataSet;
	    }

	    $this->dataSet = new CsvDataset( $source, 1, true );

	    $samples = [];
	    foreach ( $this->dataSet->getSamples() as $sample ) {
		    $samples[] = $sample[0];
	    }

	    $samples = $this->normalize( $samples );

	    $this->dataSet = new ArrayDataset( $samples, $this->dataSet->getTargets() );

	    return $this->dataSet;

    }

    public function save_ai() {
	    $this->modelManager->saveToFile( $this->ai, $this->model_file );
    }

    public function load_ai() {
	    $this->ai = $this->modelManager->restoreFromFile( $this->model_file );
    }

    public function predict( $data ) {
		return $this->ai->predict( $data );
    }

    public function train( $dataSet ) {
	    $this->randomSplit = new StratifiedRandomSplit( $dataSet, 0.1, 63452 );

	    $this->ai->train( $this->randomSplit->getTrainSamples(), $this->randomSplit->getTrainLabels() );
    }

    public function report() {
	    $results = array();
    	$predictSamples = $this->randomSplit->getTestSamples();
	    $predictedLabels = $this->ai->predict( $predictSamples );

	    $report = new ClassificationReport( $this->randomSplit->getTestLabels(), $predictedLabels );

	    $results['accuracy'] = Accuracy::score( $this->randomSplit->getTestLabels(), $predictedLabels );

	    foreach ( $report->getPrecision() as $label => $precision ) {
		    $results['precision'][$label] = $precision;
	    }

	    return $results;
    }

    public function train_from_file() {
	    $this->testDataSet = new CsvDataset( $this->train_file, 1, true );

	    $samples = [];
	    foreach ( $this->testDataSet->getSamples() as $sample ) {
		    $samples[] = $sample[0];
	    }

	    $samples = $this->normalize( $samples );

	    $this->testDataSet = new ArrayDataset( $samples, $this->testDataSet->getTargets() );

	    $randomSplit = new StratifiedRandomSplit( $this->testDataSet, 0.2, 63452 );

	    $this->ai->train( $randomSplit->getTrainSamples(), $randomSplit->getTrainLabels() );
    }

    public function create_train_csv( $data ) {
        $file = fopen( $this->train_file, 'w+' );
        fputcsv( $file, array( "feedback", "label" ), ',', '"' );
        $db = new Ai_Database();
        $traind_data = $db->get_train_data();
        foreach ( $traind_data as $row ) {
            fputcsv( $file, $row, ',', '"' );
        }
    }
}