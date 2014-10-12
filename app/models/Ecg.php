<?php

class Ecg extends Eloquent {
    private $graphs = array();
    private $pulseData = array();
    private $userMarkingsData = array();
    
    /**
     * @deprecated
     * @param int $step
     * @param int $range
     * @return array
     */
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
    
    /**
     * Get list of user graphs
     * @param int $userId
     * @return boolean|array
     */
    public function getGraphs($userId) {
        try {
            $list = DB::table('graphs')->where('user_id', '=', $userId)->orderBy('start', 'DESC')->get();
            if(empty($list)) {
                return false;
            }
            $result = array();
            foreach ($list as $item) {
                $unixStart = strtotime($item->start);
                $unixEnd = strtotime($item->end);

                $start = date('d.m.Y', $unixStart);
                $end = date('d.m.Y', $unixEnd);

                if ($start == $end) {
                    $date = $start;
                } else {
                    $date = $start . ' ' . $end;
                }

                $result[] = array(
                    'date' => $date,
                    'time' => date('G:i:s', $unixStart) . ' - ' . date('G:i:s', $unixEnd),
                    'unix_start' => $unixStart,
                    'unix_end' => $unixEnd
                );
            }
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get full plot data
     * @param array $parameters - start <unixtime>, end <unixtime>, step <int>, range <int>
     * @return array
     */
    public function getGraphData($parameters) {
        $from = $parameters['start'] + ($parameters['step'] - 1) * $parameters['range'];
        $to = $from + $parameters['range'];

        if ($to > $parameters['end']) $to = $parameters['end'];

        $data = DB::table("tp_{$parameters['user_id']}_ekg")->select('*')->whereBetween('timestamp', array("{$from}000", "{$to}000"))->get();

        $graphData = array();
        foreach ($data as $value) {
            $volts = explode('*', $value->values);
            $time = $value->timestamp;
            foreach ($volts as $volt) {
                $time += 4;
                switch (intval($volt)) {
                    case 0: $this->pulseData[] = $time; break;
                    case -1: $this->userMarkingsData[] = $time; break;
                    default: $graphData[] = array($time, intval($volt)); break;
                }
            }
        }

        if (($from + $parameters['range']) > $parameters['end']) {
            $additionalValuesCount = $parameters['range'] * 250 - count($graphData);
            $lastValue = end($graphData);
            $additionalTime = $lastValue[0];
            $additionalValue = Config::get('graph.bottom_voltage_border') + 1500;

            for ($i = 0; $i < $additionalValuesCount; $i++) {
                $additionalTime += 4;
                $graphData[] = array($additionalTime, $additionalValue);
            }
        }

        $this->getGraphGrid($graphData);

        $options = array(
            'data' => $graphData,
            'lines' => array('show' => true, 'lineWidth' => 2)
        );
        $this->graphs['ecg'][] = $this->getLine($options);
        $this->getUserMarkings();

        $this->getPulseLine();

        return $this->graphs;
    }

    private function getGraphGrid($graphData) {
        $graphTimeStart = $graphData[0][0];
        $graphTimeEnd = $graphData[count($graphData) - 1][0];

        $topVoltageBorder = Config::get('graph.top_voltage_border');
        $bottomVoltageBorder = Config::get('graph.bottom_voltage_border');

        $linesCount = 0;

        /* Vertical grid lines */
        for ($i = $graphTimeStart; $i < $graphTimeEnd; $i += 40) {
            $lineColor = ($linesCount % 5 == 0) ? '#CA8907' : '#FFAB00';

            $options = array(
                'data' => array(
                    array($i, $topVoltageBorder),
                    array($i, $bottomVoltageBorder)
                ),
                'color' => $lineColor
            );
            $this->graphs['ecg'][] = $this->getLine($options);
            $linesCount++;
        }

        $linesCount = 0;

        /* Horizontal grid lines*/
        for ($i = $bottomVoltageBorder; $i < $topVoltageBorder; $i += 150) {
            $lineColor = ($linesCount % 5 == 0) ? '#CA8907' : '#FFAB00';

            $options = array(
                'data' => array(
                    array($graphTimeStart, $i),
                    array($graphTimeEnd, $i)
                ),
                'color' => $lineColor
            );
            $this->graphs['ecg'][] = $this->getLine($options);
            $linesCount++;
        }
    }

    private function getUserMarkings() {
        if (empty($this->userMarkingsData)) {
            return false;
        }

        foreach ($this->userMarkingsData as $mark) {
            $options = array(
                'data' => array(
                    array($mark, Config::get('graph.top_voltage_border')),
                    array($mark, Config::get('graph.top_voltage_border')),
                ),
                'color' => '#3399CC',
                'lines' => array(
                    'show' => true,
                    'lineWidth' => 2
                )
            );

            $this->graphs['ecg'][] = $this->getLine($options);
        }
    }

    private function getPulseLine() {
        if (empty($this->pulseData)) {
            $this->graphs['pulse'] = array();
            return;
        }

        $pulseValues = array();
        for ($i = 1; $i < count($this->pulseData); $i++) {
            $difference = $this->pulseData[$i] - $this->pulseData[$i - 1];
            $pulse = 60000 / $difference;

            $pulseValues[] = array($this->pulseData[$i], $pulse);
        }

        $this->graphs['pulse'][] = $this->getLine(array('data' => $pulseValues));
    }

    private function getLine($options = array()) {
        $line = array(
            'data' => array(),
            'color' => '#000000',
            'shadowSize' => 0,
            'lines' => array(
                'show' => true,
                'lineWidth' => 1
            )
        );

        foreach ($options as $name => $value) {
            $line[$name] = $value;
        }

        return $line;
    }

    /**
     * Get unixtime of user graph endings
     * @param array $args - start <unixtime>, user_id <int>
     * @return string|multitype:
     */
    public function getLastTime($args){
        $startTime = $args['start'];
        $userId = $args['user_id'];
        $timeString = date("Y-m-d H:i:s", ($startTime));
        
        $result = DB::table('graphs')->select('end')
                ->where('user_id', '=', $userId)
                ->where('start', '=', $timeString)
                ->get();
        
        $timeValue = array();
        
        if(empty($result)) {
            return "Empty";
        }
        if ( count( $result ) != 1){
            return "more then 1";
        }
        
        foreach($result as $key => $val) {
            array_push($timeValue, $val->end);
        }
        return $timeValue;
    }
    
    
    /**
     * Get calendar set 
     * @param array $args - [year, month]
     * @return array
     */
    public function getCalendar($args) {
        $month = $args['month'];
        $year = $args['year'];
        $user_id = $args['user_id'];
        
        /* first element is array of day names */
        $days = array(
            array(
                0 => array('n' => Lang::get('names.monday')),
                1 => array('n' => Lang::get('names.tuesday')),
                2 => array('n' => Lang::get('names.wednesday')),
                3 => array('n' => Lang::get('names.thursday')),
                4 => array('n' => Lang::get('names.friday')),
                5 => array('n' => Lang::get('names.saturday')),
                6 => array('n' => Lang::get('names.sunday'))
            )
        );
        
        $start = $year . '-' . $month . '-01 00:00:00'; /* first day of the month */
        $end = date('Y-m-t', strtotime($start)) . ' 00:00:00'; /* last day of the month */
        
        /* get all graphs of this month */
        $graphs = DB::table('graphs')->select('*')->where('user_id', '=', $user_id)->where('start', '>', $start)->where('end', '<', $end)->get();
        
        $weeks = $this->numWeeks($month, $year); /* get number of weeks in this month */
        for($i = 1; $i < $weeks + 1; $i++) {
            $weekDays = $this->days($month, $year, $i); /* get current week days numbers */
            
            /* inserting information if there was a graph in this day */
            foreach ($weekDays as $dayIndex => $dayNumber) {
                $num = 0;
                foreach ($graphs as $graph) {
                    if(intval($dayNumber['n']) == intval(date('j', strtotime($graph->start)))) {
                        $weekDays[$dayIndex][$num]['start'] = date('d.m.Y G:i:s', strtotime($graph->start));
                        $weekDays[$dayIndex][$num]['end'] = date('d.m.Y G:i:s', strtotime($graph->end));
                        $weekDays[$dayIndex][$num]['startDay'] = date('j', strtotime($graph->start));
                        $weekDays[$dayIndex][$num]['endDay'] = date('j', strtotime($graph->end));
                        $num++;
                    }
                }
            }
            array_push($days, $weekDays);
        }
        return $days;
    }
    
    /**
     * ?
     * @param int $userId
     * @return array
     */
    public function getYears($userId) {
        $firstGraph = DB::table('graphs')->min('start');
        $firstYear = date('Y', strtotime($firstGraph));
        $currentYear = date('Y');
        $years = array();
        if($currentYear != $firstYear) {
            for($i = $firstYear; $i <= $currentYear; $i++) {
                array_push($years, $i);
            }
        } else {
            array_push($years, $currentYear);
        }
        return $years;
    }
    
    /*  */
    private function numWeeks($month, $year) {
        $num_weeks = 4;
        $first_day = $this->firstDay($month, $year);
        if($first_day != 1) $num_weeks++;
    
        $widows = $first_day - 1;
        $fw_days = 7 - $widows;
        if($fw_days == 7) $fw_days = 0;
    
        $numdays = date("t",mktime(2, 0, 0, (int)$month, 1, $year));
        if(($numdays - $fw_days) > 28) $num_weeks++;
        return $num_weeks;
    }
    
    private function firstDay($month, $year) {
        $first_day = date("w", mktime(2, 0, 0, (int)$month, 1, $year));
        if($first_day == 0) $first_day = 7; # convert Sunday
        return $first_day;
    }
    
    private function days($month, $year, $week, $num_weeks=0) {
        $days = array();
        if($num_weeks == 0) $num_weeks = $this->numWeeks($month, $year);
        $first_day = $this->firstDay($month, $year);
        $widows = $first_day - 1;
        $fw_days= 7 - $widows;
    
        if($week == 1) {
            for($i = 0; $i < $widows; $i++) $days[]['n'] = 0;
            for($i = 1; $i <= $fw_days; $i++) $days[]['n'] = $i;
            return $days;
        }
    
        if($week != $num_weeks) {
            $first = $fw_days + (($week - 2) * 7);
            for($i = $first + 1; $i <= $first + 7; $i++) $days[]['n'] = $i;
            return $days;
        }
    
        $numdays = date("t",mktime(2, 0, 0, (int)$month, 1, $year));
        $orphans = $numdays - $fw_days - (($num_weeks - 2) * 7);
        $empty = 7 - $orphans;
        for($i = ($numdays - $orphans) + 1; $i <= $numdays; $i++) $days[]['n'] = $i;
        for($i = 0; $i < $empty; $i++) $days[]['n'] = 0;
        return $days;
    }
}