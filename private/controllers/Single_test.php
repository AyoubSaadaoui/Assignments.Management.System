<?php

/**
 * single test controller
 */
class Single_test extends Controller
{

	public function index($id = '')
	{
		// code...
		$errors = array();
		if(!Auth::logged_in())
		{
			$this->redirect('login');
		}

		$tests = new tests_model();
		$row = $tests->whereOne('test_id',$id);

		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['tests','tests'];

		if($row){
			$crumbs[] = [$row->test,''];
		}

		$limit = 10;
 		$pager = new Pager($limit);
 		$offset = $pager->offset;

		$page_tab = 'view';

		$results = false;
		$quest = new Questions_model();
		$questions = $quest->where('test_id',$id);
		$total_questions = count($questions);

		$data['row'] 		= $row;
 		$data['crumbs'] 	= $crumbs;
		$data['page_tab'] 	= $page_tab;
		$data['results'] 	= $results;
		$data['questions'] 	= $questions;
		$data['total_questions'] 	= $total_questions;
		$data['errors'] 	= $errors;
		$data['pager'] 		= $pager;

		$this->view('single-test',$data);
	}

	public function addsubjective($id = '')
	{
		// code...
		$errors = array();
		if(!Auth::logged_in())
		{
			$this->redirect('login');
		}

		$tests = new tests_model();
		$row = $tests->whereOne('test_id',$id);

		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['tests','tests'];

		if($row){
			$crumbs[] = [$row->test,''];
		}

		$limit = 10;
 		$pager = new Pager($limit);
 		$offset = $pager->offset;

		$quest = new Questions_model();

 		if(count($_POST) > 0) {

			if($quest->validate($_POST)){

				//check for files
				if($myimage = upload_image($_FILES))
				{
					$_POST['image'] = $myimage;
				}

				$_POST['test_id'] = $id;
				$_POST['date'] = date("Y-m-d H:i:s");
				if(isset($_GET['type']) && $_GET['type'] == "objective"){
 					$_POST['question_type'] = 'objective';
 				}else{
 					$_POST['question_type'] = 'subjective';
 				}
				$quest->insert($_POST);
				$this->redirect('single_test/'.$id);

			}else {
				//errors
				$errors = $quest->errors;
			}
		}

		$page_tab = 'add-subjective';

		$results = false;


		$data['row'] 		= $row;
 		$data['crumbs'] 	= $crumbs;
		$data['page_tab'] 	= $page_tab;
		$data['results'] 	= $results;
		$data['errors'] 	= $errors;
		$data['pager'] 		= $pager;

		$this->view('single-test',$data);
	}
}