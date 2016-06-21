<?php

class Application_Model_Question extends Zend_Db_Table_Abstract {

    protected $_name = "question";
    protected $_primary = "id";

    public function insertdb($dataInsert) {
        $data = array(
            'exam_name' => $dataInsert['exam_name'],
            'exam_question_id' => $dataInsert['exam_question_id'],
            'title' => $dataInsert['title'],
            'A' => $dataInsert['A'],
            'B' => $dataInsert['B'],
            'C' => $dataInsert['C'],
            'D' => $dataInsert['D'],
            'result' => $dataInsert['result']
        );

        $this->insert($data);
    }

    public function getQuestionsByExamName($exam_name) {
        $select = $this->select()->where('exam_name = ?', $exam_name);
        $rows = $this->fetchAll($select);
        return $rows;
    }

    public function checkAnswers($exam_name, $data) {
        $row = $this->getQuestionsByExamName($exam_name);
        $count = 0;
        $output = array();
        for ($i = 0; $i < count($data); $i++) {
            if ($row[$i]['result'] == $data[$i]) {
                $output[$i] = TRUE;
                $count++;
            } else {
                $output[$i] = FALSE;
            }
            $this->updateAnsNum($exam_name, $row[$i]['exam_question_id'], $row[$i], $output[$i]);
        }
        return $count;
    }

    public function updateAnsNum($exam_name, $exam_question_id, $info, $check) {
        $condition = array(
            'exam_name=?'=> $exam_name,
            'exam_question_id=?'=>$exam_question_id
        );
//        $where = $this->getAdapter()->quoteInto($condition);
        $data;
        if ($check) {
            $data = array('correct_num' => ($info['correct_num'] + 1));
        } else {
            $data = array('wrong_num' => ($info['wrong_num'] + 1));
        }
        $this->update($data, $condition);
    }
    
   

}
