<?php

class Application_Model_Exam extends Zend_Db_Table_Abstract {

    protected $_name = "exam";
    protected $_primary = "id";

    public function insertdb($dataInsert) {

        $data = array(
            'exam_name' => $dataInsert['exam_name'],
            'username' => $dataInsert['username'],
            'total_question' => $dataInsert['total_question'],
            'duration' => $dataInsert['duration'],
            'status' => "private",
            'code' => urlencode(crypt($dataInsert['exam_name'], $dataInsert['username']))
        );

        $this->insert($data);
    }

    public function getAllExams($username) {
        $select = $this->select()->where('username = ?', $username);

        $rows = $this->fetchAll($select);
        return $rows;
    }

    public function getInfoOfExam($exam_name) {
        $select = $this->select()->where('exam_name = ?', $exam_name);
        $rows = $this->fetchAll($select);
        return $rows[0];
    }

    public function getInfoOfExamByCode($code) {
        $select = $this->select()->where('code = ?', $code);
        $rows = $this->fetchAll($select);
        return $rows[0];
    }

    public function ChangeStatus($exam_name, $status) {
        $data = array(
            'status' => $status
        );
        $where = $this->getAdapter()->quoteInto('exam_name = ?', $exam_name);
        $this->update($data, $where);
    }

}
