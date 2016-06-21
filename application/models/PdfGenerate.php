<?php

class Application_Model_PdfGenerate {

    function generate_exam($exam_name) {
        //get the exam information
        $exam_table = new Application_Model_Exam();
        $info_rows = $exam_table->getInfoOfExam($exam_name);
        $title_text = $info_rows['exam_name'] . "\t\t\t\t(" . $info_rows['duration'] . "mins)";
        //get all the questions
        $questions_table = new Application_Model_Question();
        $questions_rows = $questions_table->getQuestionsByExamName($exam_name);

        $pdf = new Application_Model_PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        //set up title
        $pdf->Image('https://s3-ap-southeast-2.amazonaws.com/cloudingrmit/data/media/logo.png', 10, 6, 30);
        // Arial bold 15
//           $pdf->SetTitle("test");
        $pdf->SetFont('Arial', 'B', 10);
        // Move to the right
        $pdf->Cell(60);
        // Title
        $pdf->Cell(100, 10, $title_text, 1, 0, 'C');
        // Line break
        $pdf->Ln(20);

        foreach ($questions_rows as $questions) {
            $question_text = ($questions['exam_question_id'] + 1) . "." . $questions['title'];
           
            $pdf->Cell(30);
            $pdf->Cell(0, 5, $question_text, 3, 1);
     
            $pdf->Cell(30);
            $answer_A = "A ." . $questions['A'];
            $pdf->Cell(0, 5, $answer_A, 3, 1);
            
            $pdf->Cell(30);
            $answer_B = "B ." . $questions['B'];            
            $pdf->Cell(0, 5, $answer_B, 3, 1);
                        
            $pdf->Cell(30);
            $answer_C = "C ." . $questions['C'];
            $pdf->Cell(0, 5, $answer_C, 3, 1);
            
            $pdf->Cell(30);
            $answer_D = "D ." . $questions['D'];
            $pdf->Cell(0, 5, $answer_D, 3, 1);
            $pdf->Cell(0, 10, "", 3, 1);
        }

        $pdf->Output();
    }

}
