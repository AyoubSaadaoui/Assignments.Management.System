<?php

/**
 * Marked controller
 */
class Marked extends Controller
{
    function index() {

        if(!Auth::logged_in()) {

            $this->redirect('/login');
        }

        if(!Auth::access('teacher'))
		{
			$this->redirect('access-denied');
		}

        $tests = new Tests_model();

		$school_id = Auth::getSchool_id();

		if(Auth::access('admin')){

			$query = "select * from tests where school_id = :school_id order by id desc";
			$arr['school_id'] = $school_id;

			if(isset($_GET['find']))
	 		{
	 			$find = '%' . $_GET['find'] . '%';
	 			$query = "select * from tests where school_id = :school_id && (test like :find) order by id desc";
	 			$arr['find'] = $find;
	 		}

			$data = $tests->query($query,$arr);
 		}else{

 			$mytable = "class_teachers";

			$query = "select * from $mytable where user_id = :user_id && disabled = 0";
 			$arr['user_id'] = Auth::getUser_id();

			if(isset($_GET['find']))
	 		{
	 			$find = '%' . $_GET['find'] . '%';
	 			$query = "select tests.test, {$mytable}.* from $mytable join tests on tests.test_id = {$mytable}.test_id where {$mytable}.user_id = :user_id && {$mytable}.disabled = 0 && tests.test like :find ";
	 			$arr['find'] = $find;
	 		}

			$arr['stud_classes'] = $tests->query($query,$arr);

			//read all tests from the selectd classes
			$data = array();
			if($arr['stud_classes']){
				foreach ($arr['stud_classes'] as $key => $arow) {
					// code...
 					$query = "select * from tests where class_id = :class_id";
 					$a = $tests->query($query,['class_id'=>$arow->class_id]);
 					if(is_array($a)){
 						$data = array_merge($data,$a);
 					}
				}
			}



 		}

 		//get all submitted tests
		$marked = array();
		if($data){
			if(count($data) > 0){

				$all_tests = array_column($data, 'test_id');
				$all_tests_string = "'".implode("','", $all_tests)."'";

					// code...
						$query = "select * from answered_tests where test_id in ($all_tests_string) && submitted = 1 && marked = 1 order by id desc";

						$marked = $tests->query($query);

						if(is_array($marked)){

							foreach ($marked as $key => $value) {
								// code...
								$test_details = $tests->whereOne('test_id',$marked[$key]->test_id);
								$marked[$key]->test_details = $test_details;


							}

						}

			}
		}

		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['Marked','marked'];

		$this->view('marked',[
			'crumbs'=>$crumbs,
			'test_rows'=>$marked
		]);


    }
}
