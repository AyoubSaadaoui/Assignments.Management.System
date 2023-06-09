<?php

function get_var($key, $default = "") {

    if(isset($_POST[$key])) {

        return $_POST[$key];
    }

    return $default;
}

function get_selected($key, $value) {

    if(isset($_POST[$key])) {

        if($_POST[$key] == $value) {

            return "selected";
        }

        return "";
    }
}

function esc($var) {

    return htmlspecialchars($var);
}

function random_string($length) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $id = '';

    for ($i = 0; $i < $length; $i++) {
        $random = rand(0, strlen($characters) - 1);
        $id .= $characters[$random];
    }

    return $id;
}

function get_date($date) {

    $date = date("d.m.y", strtotime($date));
    return str_replace(".", "/", $date);
}

// for testing
function schow($data) {

    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function get_image($image, $gender = 'male') {

    if(!file_exists($image)) {
        $image = ASSETS."/user_male.png";
        if($gender == 'female') {
          $image = ASSETS."/user_female.png";
        }
    }else
    {
        $class = new Image();
 		$image = ROOT . "/" . $class->profile_thumb($image);
    }

    return $image;
}

function views_path($view)
{
	if(file_exists("../private/views/" . $view . ".inc.php")) {
		return ("../private/views/" . $view . ".inc.php");
	}else{
		return ("../private/views/404.view.php");
	}
}

function upload_image($FILES)
{
	if(count($FILES) > 0)
	{

		//we have an image
		$allowed[] = "image/jpeg";
		$allowed[] = "image/png";

		if($FILES['image']['error'] == 0 && in_array($FILES['image']['type'], $allowed))
		{
			$folder = "uploads/";
			if(!file_exists($folder)){
				mkdir($folder,0777,true);
			}
			$destination = $folder . time() . "_" . $FILES['image']['name'];
			move_uploaded_file($FILES['image']['tmp_name'], $destination);
			return $destination;
		}

	}

	return false;
}

function has_taken_test($test_id)
{

	return "No";
}

function can_take_test($my_test_id)
{
	$class = new Classes_model();
	$mytable = "class_students";
	if(Auth::getRank() != "student"){
		return false;
	}

	$query = "select * from $mytable where user_id = :user_id && disabled = 0";
	$data['stud_classes'] = $class->query($query,['user_id'=>Auth::getUser_id()]);

	$data['student_classes'] = array();
	if($data['stud_classes']){
		foreach ($data['stud_classes'] as $key => $arow) {
			// code...
			$data['student_classes'][] = $class->whereOne('class_id',$arow->class_id);
		}
	}

	//collect class id's
	$class_ids = [];
	foreach ($data['student_classes'] as $key => $class_row) {
		// code...
		$class_ids[] = $class_row->class_id;
	}

	$id_str = "'" . implode("','", $class_ids) . "'";
	$query = "select * from tests where class_id in ($id_str)";

	$tests_model = new Tests_model();
	$tests = $tests_model->query($query);

	$my_tests = array_column($tests, 'test_id');
	if(in_array($my_test_id, $my_tests)){
		return true;
	}
	return false;
}


function get_answer($saved_answers,$id)
{

	if(!empty($saved_answers)){

		foreach ($saved_answers as $row) {
			// code...
			if($id == $row->question_id)
			{
				return $row->answer;
			}
		}
	}

	return '';
}

function convertPercentage($percentage) {
    // Remove the percentage sign and convert the value to a float
    $value = floatval(trim($percentage, '%'));

    // Round the value to the nearest integer
    $roundedValue = round($value);

    return $roundedValue;
}



function get_answer_percentage($test_id,$user_id)
{

	$quest = new Questions_model();
	$questions = $quest->query('select * from test_questions where test_id = :test_id',['test_id'=>$test_id]);

	$answers = new Answers_model();
	$query = "select question_id,answer from answers where user_id = :user_id && test_id = :test_id ";
	$saved_answers = $answers->query($query,[
		'user_id' => $user_id,
		'test_id' => $test_id,
	]);

    $total_answer_count = 0;
    if(!empty($questions))
    {
        foreach ($questions as $quest) {
            // code...
            $answer = get_answer($saved_answers,$quest->id);
            if(trim($answer) != ""){
                $total_answer_count++;
            }
        }
    }

    if($total_answer_count > 0)
    {
        $total_questions = count($questions);

        $percentage = ($total_answer_count / $total_questions) * 100;
		$convertedPercentage = convertPercentage($percentage);

		return $convertedPercentage;
    }

    return 0;
}

function get_mark($saved_answers,$id)
{

    if(!empty($saved_answers)){

        foreach ($saved_answers as $row) {
            // code...
            if($id == $row->question_id)
            {
                return $row->answer_mark;
            }
        }
    }
}

function get_answer_mark($saved_answers,$id)
{

    if(!empty($saved_answers)){

        foreach ($saved_answers as $row) {
            // code...
            if($id == $row->question_id)
            {
                return $row->answer_mark;
            }
        }
    }

    return '';
}


function get_mark_percentage($test_id,$user_id)
{

	$quest = new Questions_model();
	$questions = $quest->query('select * from test_questions where test_id = :test_id',['test_id'=>$test_id]);

	$answers = new Answers_model();
	$query = "select question_id,answer,answer_mark from answers where user_id = :user_id && test_id = :test_id ";
	$saved_answers = $answers->query($query,[
		'user_id' => $user_id,
		'test_id' => $test_id,
	]);

    $total_answer_count = 0;
    if(!empty($questions))
    {
        foreach ($questions as $quest) {
            // code...
            $answer = get_mark($saved_answers,$quest->id);
            if(trim($answer) > 0){
                $total_answer_count++;
            }
        }
    }

    if($total_answer_count > 0)
    {
        $total_questions = count($questions);

        $percentage = ($total_answer_count / $total_questions) * 100;
		$convertedPercentage = convertPercentage($percentage);

		return $convertedPercentage;
    }

    return 0;
}

function get_score_percentage($test_id,$user_id)
{

	$quest = new Questions_model();
	$questions = $quest->query('select * from test_questions where test_id = :test_id',['test_id'=>$test_id]);

	$answers = new Answers_model();
	$query = "select question_id,answer,answer_mark from answers where user_id = :user_id && test_id = :test_id ";
	$saved_answers = $answers->query($query,[
		'user_id' => $user_id,
		'test_id' => $test_id,
	]);

    $total_answer_count = 0;
    if(!empty($questions))
    {
        foreach ($questions as $quest) {
            // code...
            $answer = get_mark($saved_answers,$quest->id);
            if(trim($answer) == 1){
                $total_answer_count++;
            }
        }
    }

    if($total_answer_count > 0)
    {
        $total_questions = count($questions);

		$percentage = ($total_answer_count / $total_questions) * 100;
		$convertedPercentage = convertPercentage($percentage);

		return $convertedPercentage;
    }
}

function get_unsubmitted_tests()
{
	if(Auth::getRank() == "student")
	{

		$tests_class = new Tests_model();
		$query = "select id from tests where class_id in (select class_id from class_students where user_id = :user_id) and test_id not in (select test_id from answered_tests where user_id = :user_id && submitted = 1) && disabled = 0";

		$data = $tests_class->query($query,['user_id'=>Auth::getUser_id()]);

		if($data){
			return count($data);
		}
	}

	return 0;
}

function get_unsubmitted_test_rows()
{
	if(Auth::getRank() == "student")
	{
		$tests_class = new Tests_model();
		$query = "select test_id from tests where class_id in (select class_id from class_students where user_id = :user_id) and test_id not in (select test_id from answered_tests where user_id = :user_id && submitted = 1)";

		$data = $tests_class->query($query,['user_id'=>Auth::getUser_id()]);

		if($data){
			return array_column($data,'test_id');
		}
	}

	return [];
}

function count_($array) {
    return is_array($array) ? count($array) : 0;
}