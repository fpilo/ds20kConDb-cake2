<?php 
class Ds20condbSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_acos_lft_rght' => array('column' => array('lft', 'rght'), 'unique' => 0), 'idx_acos_alias' => array('column' => 'alias', 'unique' => 0), 'idx_acos_model_foreign_key' => array('column' => array('model', 'foreign_key'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_aros_lft_rght' => array('column' => array('lft', 'rght'), 'unique' => 0), 'idx_aros_alias' => array('column' => 'alias', 'unique' => 0), 'idx_aros_model_foreign_key' => array('column' => array('model', 'foreign_key'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'aco_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1), 'FK_aro_id' => array('column' => 'aro_id', 'unique' => 0), 'FK_aco_id' => array('column' => 'aco_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $devices = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'Devices_LocationID' => array('column' => 'location_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $events = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $histories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'event_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'comment' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'EventID' => array('column' => 'event_id', 'unique' => 0), 'ItemID' => array('column' => 'item_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $item_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'species' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $item_types_long_blobs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'long_blob_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'item_types_long_blobs.itemsID' => array('column' => 'item_type_id', 'unique' => 0), 'item_types_long_blobs.long_blobsID' => array('column' => 'long_blob_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $items = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'unique', 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'item_type_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'state_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'manufacturer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'item_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'code' => array('column' => 'code', 'unique' => 1), 'Type' => array('column' => 'item_type_id', 'unique' => 0), 'Location' => array('column' => 'location_id', 'unique' => 0), 'Status' => array('column' => 'state_id', 'unique' => 0), 'Manufacturer' => array('column' => 'manufacturer_id', 'unique' => 0), 'Project' => array('column' => 'project_id', 'unique' => 0), 'CompositeItem' => array('column' => 'item_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $items_medium_blobs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'medium_blob_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'items_medium_blobs.itemsID' => array('column' => 'item_id', 'unique' => 0), 'items_medium_blobs.medium_blobsID' => array('column' => 'medium_blob_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $locations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'address' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'phone_number' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'contact' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $long_blobs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'content' => array('type' => 'binary', 'null' => false, 'default' => NULL),
		'comment' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 250, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $manufacturers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'address' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'phone_number' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'contact' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $measurement_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name_UNIQUE' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $measurements = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'history_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'device_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'measurement_type_id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'key' => 'index', 'comment' => 'To select the right table for the measurement data: dim=1 ==> 1D_measurement_data; dim=2 ==> 2D_measurement_data; ...'),
		'start' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'stop' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'MeasurementSeries_HistoryID' => array('column' => 'history_id', 'unique' => 0), 'MeasurementSeries_DeviceID' => array('column' => 'device_id', 'unique' => 0), 'MeasurementSeries_UserID' => array('column' => 'user_id', 'unique' => 0), 'MeasurementSeries_ItemID' => array('column' => 'item_id', 'unique' => 0), 'MeasurementSeries_MeasurementTypeID' => array('column' => 'measurement_type_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $measuring_points = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'measurement_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'MeasuringPoints_MeasurementID' => array('column' => 'measurement_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $medium_blobs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'content' => array('type' => 'binary', 'null' => true, 'default' => NULL),
		'comment' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 250, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $parameters = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name_UNIQUE' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $projects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $readings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'measuring_point_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'parameter_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'value' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'Readings_MeasuringPointID' => array('column' => 'measuring_point_id', 'unique' => 0), 'Readings_ParameterID' => array('column' => 'parameter_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	public $states = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
	public $transfers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'history_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'unique'),
		'shipping_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'from_location_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'to_location_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'history_id_UNIQUE' => array('column' => 'history_id', 'unique' => 1), 'Transfers_HistoryID' => array('column' => 'history_id', 'unique' => 0), 'Transfers_FromLocationID' => array('column' => 'from_location_id', 'unique' => 0), 'Transfers_ToLocationID' => array('column' => 'to_location_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'unique', 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'collate' => 'latin1_german1_ci', 'charset' => 'latin1'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => 'username', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_german1_ci', 'engine' => 'InnoDB')
	);
}
