<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

class PHPExcel_Lib extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
