<?php
class Ai_Database {

    private $db;

    public function __construct() {
        require './app/configs/config.php';
        $this->db = new mysqli( $db_config['host'], $db_config['user'], $db_config['pass'], $db_config['db'] );
    }

    public function training_table( $limit = 25 ) {
	    $results = array();
	    $query = $this->db->query( "
        SELECT 
        fn.name,
        ff.feedback,
        ff._id,
        ff.label
        FROM wp_feedback_names fn
        LEFT JOIN wp___pirate_feedback_feedback1 ff ON ff.feedback_id = fn.id
        WHERE ff.label IS NOT NULL
        ORDER BY RAND()
        LIMIT {$limit}
        " );
	    while ( $response = $query->fetch_assoc() ) {
		    array_push( $results, array( $response['name'], $response['feedback'], $response['label'] ) );
	    }

	    return $results;
    }

	public function predict_table( $limit = 25 ) {
		$results = array();
		$query = $this->db->query( "
        SELECT 
        fn.name,
        ff.feedback,
        ff._id,
        ff.label
        FROM wp_feedback_names fn
        LEFT JOIN wp___pirate_feedback_feedback1 ff ON ff.feedback_id = fn.id
        WHERE ff.label IS NULL
        ORDER BY RAND()
        LIMIT {$limit}
        " );
		while ( $response = $query->fetch_assoc() ) {
			array_push( $results, array( $response['name'], $response['feedback'], $response['label'] ) );
		}

		return $results;
	}

    public function get_random_unlabeled_feedback( $name = false, $limit = 1 ) {
        $conditions = '';
        if ( $name ) {
            $conditions = ' AND fn.name = "' . $name . '" ';
        }

        $query = $this->db->query( "
        SELECT 
        fn.name,
        ff.feedback,
        ff._id,
        ff.label
        FROM wp_feedback_names fn
        LEFT JOIN wp___pirate_feedback_feedback1 ff ON ff.feedback_id = fn.id
        WHERE ff.label IS NULL 
        " . $conditions . "
        ORDER BY RAND()
        LIMIT {$limit}
        " );
        return $query->fetch_assoc();
    }

    public function update_feedback_label( $id, $label ) {
        return $this->db->query( "UPDATE wp___pirate_feedback_feedback1 SET `label`='$label' WHERE `_id`='$id'" );
    }

    public function test() {
        $query = $this->db->query( "
        SELECT 
        fn.name,
        ff.feedback,
        ff._id
        FROM wp_feedback_names fn
        LEFT JOIN wp___pirate_feedback_feedback1 ff ON ff.feedback_id = fn.id
        LIMIT 1000
        " );

        while ( $response = $query->fetch_assoc() ) {
            var_dump( $response );
        }
    }

    public function get_train_data() {
        $results = array();
        $query = $this->db->query( "
        SELECT 
        fn.name,
        ff.feedback,
        ff._id,
        ff.label
        FROM wp_feedback_names fn
        LEFT JOIN wp___pirate_feedback_feedback1 ff ON ff.feedback_id = fn.id
        WHERE ff.label IS NOT NULL
        ORDER BY ff.label ASC
        LIMIT 1000
        " );

        while ( $response = $query->fetch_assoc() ) {
            array_push( $results, array( $response['feedback'], $response['label'] ) );
        }

        return $results;
    }

}