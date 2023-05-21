<?php


/**
 * classes controller
 */
class Classes extends Controller
{

	public function index()
	{
		// code...
		if(!Auth::logged_in())
		{
			$this->redirect('login');
		}

		$classes = new Classes_model();

		$school_id = Auth::getSchool_id();

		if(Auth::access('admin')){
			$data = $classes->query("select * from classes where school_id = :school_id order by id desc",['school_id'=>$school_id]);
 		}else{

 			$class = new Classes_model();
 			$mytable = "class_students";
 			if(Auth::getRank() == "teacher"){
 				$mytable = "class_teachers";
 			}

			$query = "select * from $mytable where user_id = :user_id && disabled = 0";
			$arr['stud_classes'] = $class->query($query,['user_id'=>Auth::getUser_id()]);

			$data = array();
			if($arr['stud_classes']){
				foreach ($arr['stud_classes'] as $key => $arow) {
					// code...
					$data[] = $class->whereOne('class_id',$arow->class_id);
				}
			}

 		}

		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['Classes','classes'];

		$this->view('classes',[
			'crumbs'=>$crumbs,
			'rows'=>$data
		]);
	}

	public function add()
	{
		// code...
		if(!Auth::logged_in())
		{
			$this->redirect('login');
		}

		$errors = array();
		if(count($_POST) > 0)
 		{

			$classes = new Classes_model();
			if($classes->validate($_POST))
 			{

 				$_POST['date'] = date("Y-m-d H:i:s");

 				$classes->insert($_POST);
 				$this->redirect('classes');
 			}else
 			{
 				//errors
 				$errors = $classes->errors;
 			}
 		}

 		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['Classes','classes'];
		$crumbs[] = ['Add','classes/add'];

		$this->view('classes.add',[
			'errors'=>$errors,
			'crumbs'=>$crumbs,

		]);
	}

	public function edit($id = null)
	{
		// code...
		if(!Auth::logged_in())
		{
			$this->redirect('login');
		}

		$classes = new Classes_model();

		$errors = array();
		if(count($_POST) > 0 && Auth::access('teacher') && Auth::i_own_content($row))
 		{

			if($classes->validate($_POST))
 			{

 				$classes->update($id,$_POST);
 				$this->redirect('classes');
 			}else
 			{
 				//errors
 				$errors = $classes->errors;
 			}
 		}

 		$row = $classes->where('id',$id);

 		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['Classes','classes'];
		$crumbs[] = ['Edit','classes/edit'];

		if(Auth::access('teacher') && Auth::i_own_content($row)){

			$this->view('classes.edit',[
				'row'=>$row,
				'errors'=>$errors,
				'crumbs'=>$crumbs,
			]);
		}else{
			$this->view('access-denied');
		}
	}

	public function delete($id = null)
	{
		// code...
		if(!Auth::logged_in())
		{
			$this->redirect('login');
		}


		$classes = new Classes_model();

		$errors = array();

		if(count($_POST) > 0 && Auth::access('teacher') && Auth::i_own_content($row))
 		{

 			$classes->delete($id);
 			$this->redirect('classes');

 		}

 		$row = $classes->where('id',$id);

 		$crumbs[] = ['Dashboard',''];
		$crumbs[] = ['Classes','classes'];
		$crumbs[] = ['Delete','classes/delete'];

		if(Auth::access('teacher') && Auth::i_own_content($row)){

			$this->view('classes.delete',[
				'row'=>$row,
	 			'crumbs'=>$crumbs,
			]);
		}else{
			$this->view('access-denied');
		}
	}

}