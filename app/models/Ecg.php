<?php

class Ecg extends Eloquent {
    
    public function getExampleData($step) {
        $from = $step * 10 - 10;
        $to = $step * 10;
        
        $data = DB::table('ecg_example')->select('*')->whereBetween('id', array($from, $to))->get();
        $result = array();
        foreach($data as $key => $val) {
            $volts = explode('*', $val->values);
            $time = $val->timestamp;
            foreach($volts as $volt) {
                $time += 4;
                if($volt != 0) {
                    array_push($result, array(intval(substr($time, -7)), intval($volt)));
                }
        
            }
        }
        return $result;
    }
    
}