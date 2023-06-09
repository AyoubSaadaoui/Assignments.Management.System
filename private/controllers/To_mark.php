<?php

/**
 * to-mark controller
 */
class To_mark extends Controller
{
    function index() {

        if(!Auth::logged_in()) {

            $this->redirect('/login');
        }

        if(!Auth::access('teacher'))
		{
			$this->redirect('access-denied');
		}

        $test = new Tests_model();
		// $to_mark_count = $test->get_to_mark_count();

		$school_id = Auth::getSchool_id();

		if(Auth::access('admin')){

			$query = "select * from answered_tests where test_id in (select test_id from tests where school_id = :school_id) && submitted = 1 && marked = 0 order by id desc";
			$arr['school_id'] = $school_id;

			// search
			if(isset($_GET['find']))
	 		{
	 			$find = '%' . $_GET['find'] . '%';
	 			$query = "select * from tests where school_id = :school_id && (test like :find) order by id desc";
	 			$arr['find'] = $find;
	 		}

			$to_mark = $test->query($query,$arr);
 		}else{

 			$mytable = "class_teachers";
  			$arr['user_id'] = Auth::getUser_id();

 		 	$query = "select * from answered_tests where test_id in (select test_id from tests where class_id in (SELECT class_id FROM `class_teachers` WHERE user_id = :user_id)) && submitted = 1 && marked = 0 order by id desc";
  		 	$to_mark = $test->query($query,$arr);

			/*
			if(isset($_GET['find']))
	 		{
	 			$find = '%' . $_GET['find'] . '%';
	 			$query = "select tests.test, {$mytable}.* from $mytable join tests on tests.test_id = {$mytable}.test_id where {$mytable}.user_id = :user_id && {$mytable}.disabled = 0 && tests.test like :find ";
	 			$arr['find'] = $find;
	 		}
			*/


 		}

		if($to_mark){
			//get test row data
			foreach ($to_mark as $key => $value) {
				// code...
				$a = $test->whereOne('test_id',$value->test_id);
				if($a){
					$to_mark[$key]->test_details = $a;
				}
			}
		}


		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['To Mark','to_mark'];

		$this->view('to-mark',[
			'crumbs'=>$crumbs,
			'test_rows'=>$to_mark,
		]);


    }
}
