<?php

class Ecg extends Eloquent {
    
    public function getExampleData($step, $range) {
        $start = substr(DB::table('ecg_example')->min('timestamp'), 0, -3);
        $from = $start + (($step-1) * $range);
        $to = $from + $range;
        
        $data = DB::table('ecg_example')->select('*')->whereBetween('timestamp', array($from . '000', $to . '000'))->get();
        $result = array();
        foreach($data as $key => $val) {
            $volts = explode('*', $val->values);
            $time = $val->timestamp;
            foreach($volts as $volt) {
                $time += 4;
                if($volt != 0) {
                    array_push($result, array(intval(substr($time, -8)), intval($volt)));
                }
        
            }
        }
        return $result;
    }
    
    public function getGraphs($userId) {
        try {
            $list = DB::table('graphs')->where('user_id', '=', $userId)->get();
            if(empty($list)) {
                return false;
            }
            return $list;
        } catch (Exception $e) {
            return false;
        }
    }
    
}