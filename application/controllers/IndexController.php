<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        // action body
        $this->_helper->layout->disableLayout();
        if (Zend_Auth::getInstance()->hasIdentity()) {
            
        }
       
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($this->getRequest()->getParam("code") != null) {
                
            } else {
                $authAdapter = $this->getAuthAdapter();
                $username = (null != $this->getRequest()->getParam("username")) ? $this->getRequest()->getParam("username") : "wrong";
                $password = (null != $this->getRequest()->getParam("password")) ? $this->getRequest()->getParam("password") : "wrong";
                $authAdapter->setIdentity($username)->setCredential($password);
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);
                if ($result->isValid()) {
                    $identity = $authAdapter->getResultRowobject();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);
                    $this->view->login_error = "";
                    $this->_redirect(root_url . '/user/index');
                } else {
                    if ($username == "wrong" || $password == "wrong") {
                        $this->view->login_error = "You must fill all the fields";
                    } else {
                        $this->view->login_error = "Wrong email or password";
                    }
                }
            }
        }
        $this->view->page_name = "Welcome to Service Monket";
    }

    public function examCodeCheckAction() {
        // action body
    }

    public function headerAction() {
        // action body
    }

    private function getAuthAdapter() {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('user')->setIdentityColumn("username")->setCredentialColumn("password");
        return $authAdapter;
    }

    public function checkCodeAction() {
        //check whether it is valid
        $authAdapter = $this->getExamAdapter();
        $exam_code = (null != $this->getRequest()->getParam("code")) ? $this->getRequest()->getParam("code") : "wrong";
        $authAdapter->setIdentity($exam_code)->setCredential($exam_code);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        if ($result->isValid()) {

            //get the exam information
            $exam_table = new Application_Model_Exam();
            $info_rows = $exam_table->getInfoOfExamByCode($exam_code);
            $this->view->basic_info = $info_rows;

            //get all the questions
            $questions_table = new Application_Model_Question();
            $questions_rows = $questions_table->getQuestionsByExamName($info_rows['exam_name']);
            $this->view->questions = $questions_rows;

            //set the title
            $this->view->page_name = $info_rows['exam_name'];
//        $this->view->page_name = "test";
            $this->_helper->viewRenderer('exam/start-exam', null, true);
        }
        else{
            $this->_helper->layout->disableLayout();
            $this->view->code_error="No such exam's code";
//            $this->redirect(root_url."/index/index");
            $this->_helper->viewRenderer('index/index', null, true);
        }
    }
        private function getExamAdapter() {
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
            $authAdapter->setTableName('exam')->setIdentityColumn("code")->setCredentialColumn("code");
            return $authAdapter;
        }

    }
    