<?php
require_once 'class-database.php';
require_once 'class-lala.php';

class Ai_Api {

	private $req;

	private $data;

	private $db;

	public function __construct( $request, $data ) {
		$this->req = null;
		if ( isset( $request['req'] ) ) $this->req = $request['req'];
		$this->data = $data;

		$this->db = new Ai_Database();
	}

	public function api() {
		switch ( $this->req ) {
            case 'predict':
                $response = $this->predict();
                break;
            case 'train':
                $response = $this->train();
                break;
			case 'get_prediction':
				$response = $this->get_prediction();
				break;
			case 'new_training':
				$response = $this->new_training();
				break;
			case 'training_table':
				$response = $this->get_training_table();
				break;
			case 'random_feedback':
				$response = $this->get_random_feedback();
				break;
			case 'update_feedback_label':
				$response = $this->update_feedback_label();
				break;
			default:
				$response = array( 'code' => 202, 'message' => 'Won\'t process further', 'data' => null );
				break;
		}

		return json_encode( $response );
	}

	private function predict() {
        $response = array();
        if ( is_array( $this->data ) && ! empty( $this->data ) ) {
            $ai = new Ai_Lala();
            $original = $this->data;

            $data = $this->db->training_table( 10000 );
            $samples = array();
            $targets = array();
            foreach ( $data as $sample ) {
                array_push( $samples, $sample[1] );
                array_push( $targets, $sample[2] );
            }

            $count = sizeof( $samples );

            $dataSet = $ai->build_data_set( array( 'samples' => $samples, 'labels' => $targets ) );
            $ai->train( $dataSet );
            $ai->save_ai();

            $samples = array_merge( $samples, $original );

            $data = $ai->normalize( $samples );

            $predictions = $ai->predict( $data );
            foreach ( array_slice( $predictions, $count ) as $index => $prediction ) {
                array_push( $response, array( $original[$index], $prediction ) );
            }
        }
        return $response;
    }

	private function train() {
	    $response = array();
	    if ( is_array( $this->data ) && ! empty( $this->data ) ) {
            $ai = new Ai_Lala();
            $data = $this->db->training_table( 10000 );
            $samples = array();
            $targets = array();
            foreach ( $data as $sample ) {
                array_push( $samples, $sample[1] );
                array_push( $targets, $sample[2] );
            }
            foreach ( $this->data as $sample ) {
                array_push( $samples, $sample[0] );
                array_push( $targets, $sample[1] );
            }
            $dataSet = $ai->build_data_set( array( 'samples' => $samples, 'labels' => $targets ) );
            $ai->train( $dataSet );
            $ai->save_ai();
            return $ai->report();
        }
        return $response;
    }

	private function get_prediction() {
		$ai = new Ai_Lala();
		$samples = array( $this->data->feedback );

		$data = $this->db->training_table( 10000 );
		$samples = array();
		$targets = array();
		foreach ( $data as $sample ) {
			array_push( $samples, $sample[1] );
			array_push( $targets, $sample[2] );
		}

		$dataSet = $ai->build_data_set( array( 'samples' => $samples, 'labels' => $targets ) );
		$ai->train( $dataSet );
		$ai->save_ai();

		array_push( $samples, $this->data->feedback );

		$data = $ai->normalize( $samples );

		$prediction = $ai->predict( array( $data[sizeof( $data ) - 1] ) );
		return $prediction;
	}

	private function new_training() {
		$data = $this->db->training_table( 10000 );
		$samples = array();
		$targets = array();
		foreach ( $data as $sample ) {
			array_push( $samples, $sample[1] );
			array_push( $targets, $sample[2] );
		}

		$ai = new Ai_Lala();

		$dataSet = $ai->build_data_set( array( 'samples' => $samples, 'labels' => $targets ) );
		$ai->train( $dataSet );
		$ai->save_ai();

		return $ai->report();
	}

	private function get_training_table() {
		return $this->db->training_table();
	}

	private function update_feedback_Label() {
		return $this->db->update_feedback_label( $this->data->id, $this->data->label );
	}

	private function get_random_feedback() {
		return $this->db->get_random_unlabeled_feedback( 'other' );
	}

}