<?php

class Application_Model_Examiner extends Zend_Db_Table_Abstract {

    protected $_name = "examiner";
    protected $_primary = "id";

    public function insertdb($dataInsert) {

        $data = array(
            'examiner_name' => $dataInsert['examiner_name'],
            'exam_name' => $dataInsert['exam_name'],
            'rank' => $dataInsert['rank'],
            'possible_mark' => $dataInsert['possible_mark'],
            'mark' => $dataInsert['mark'],
            'duration' => $dataInsert['duration'],
            'possible_duration' => $dataInsert['possible_duration']
        );

        $this->insert($data);
    }

    public function getAnalysisDuration($exam_name) {
        $select = $this->select()->where('exam_name = ?', $exam_name);
        $rows = $this->fetchAll($select);
        $highest_duration = $this->convert_time_int($rows[0]['duration']);
        $lowest_duration = $this->convert_time_int($rows[0]['duration']);
        $average_duration = 0;
        $total_duration = 0;
        $count = 0;
        $hdgroup = 0;
        $dgroup = 0;
        $cgroup = 0;
        $pgroup = 0;
        $fgroup = 0;
        $total = $this->convert_time_int($rows[0]['possible_duration']);
        $gradeGroup = array("hd" => 0, "d" => 0, "c" => 0, "p" => 0, "f" => 0);
        foreach ($rows as $row) {
            $count++;
            $duration_item = $this->convert_time_int($row['duration']);
            $total_duration+=$duration_item;
            if ($lowest_duration > $duration_item) {
                $lowest_duration = $duration_item;
            }
            if ($highest_duration < $duration_item) {
                $highest_duration = $duration_item;
            }
            switch ($duration_item) {
                case ($duration_item > $total * 0.8):
                    $gradeGroup['hd'] ++;
                    break;
                case ($duration_item > $total * 0.6):
                    $gradeGroup['d'] ++;
                    break;
                case ($duration_item > $total * 0.4):
                    $gradeGroup['c'] ++;
                    break;
                default :
                    $gradeGroup['p'] ++;
                    break;
            }
        }
        $average_duration = $total_duration / $count;
        $data = array("lowestDuration" => $this->convert_time_format($lowest_duration),
            "highestDuration" => $this->convert_time_format($highest_duration),
            "averageDuration" => $this->convert_time_format($average_duration),
            "duration_group" => $gradeGroup,
            "total" => $rows[0]['possible_duration']
        );
        return $data;
    }

    public function getAnalysisMark($exam_name) {
        $select = $this->select()->where('exam_name = ?', $exam_name);
        $rows = $this->fetchAll($select);
        $highestMark = $rows[0]['mark'];
        $lowestMark = $rows[0]['mark'];
        $averageMark = 0;
        $totalMarks = 0;
        $count = 0;
        $hdgroup = 0;
        $dgroup = 0;
        $cgroup = 0;
        $pgroup = 0;
        $fgroup = 0;
        $total = $rows[0]['possible_mark'];
        $gradeGroup = array("hd" => 0, "d" => 0, "c" => 0, "p" => 0, "f" => 0);
        $rankArr = array("hard" => 0, "medium" => 0, "easy" => 0);
        foreach ($rows as $row) {
            $count++;
            $totalMarks+=$row['mark'];
            if ($lowestMark > $row['mark']) {
                $lowestMark = $row['mark'];
            }
            if ($highestMark < $row['mark']) {
                $highestMark = $row['mark'];
            }
            switch ($row['mark']) {
                case ($row['mark'] > $total * 0.8):
                    $gradeGroup['hd'] ++;
                    break;
                case ($row['mark'] > $total * 0.7):
                    $gradeGroup['d'] ++;
                    break;
                case ($row['mark'] > $total * 0.6):
                    $gradeGroup['c'] ++;
                    break;
                case ($row['mark'] > $total * 0.5):
                    $gradeGroup['p'] ++;
                    break;
                default :
                    $gradeGroup['f'] ++;
                    break;
            }

            switch ($row['rank']) {
                case "Hard":
                    $rankArr['hard'] ++;
                    break;
                case "Medium":
                    $rankArr['medium'] ++;
                    break;
                case "Easy":
                    $rankArr['easy'] ++;
                    break;
            }
        }
        $averageMark =round($totalMarks / $count,3);
//        $averageMark=$count."/".$totalMarks;
        $rankWidth = array(
            "hard" =>$rankArr['hard']/$count*100 , 
            "medium" =>$rankArr['medium']/$count*100, 
            "easy" => $rankArr['easy']/$count*100);

        $data = array("lowestMark" => $lowestMark,
            "highestMark" => $highestMark,
            "averageMark" => $averageMark,
            "gradeGroup" => $gradeGroup,
            "total" => $total,
            "rankArr" => $rankArr,
            'rankWidth'=>$rankWidth,
            'count'=>$count
        );
        return $data;
    }

    private function convert_time_int($time) {
        $time_arr = explode(":", $time);
        $data = $time_arr[0] * 60 + $time_arr[1];
        return $data;
    }

    private function convert_time_format($time) {
        $time_total = intval($time);
        $time1 = (round($time_total / 60) < 10) ? ("0" . round($time_total / 60)) : round($time_total / 60);
        $time2 = (round($time_total % 60) < 10) ? ("0" . round($time_total % 60)) : round($time_total % 60);
        $output = $time1 . ":" . $time2;
        return $output;
    }

}
