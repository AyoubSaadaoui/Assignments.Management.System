<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

        <!-- style="width: 1000px;" -->
        <div class="container-fluid w-75 p-4 mx-auto shadow" >
        <?php $this->view('includes/crumbs', ['crumbs'=>$crumbs]); ?>

        <?php if($row) :?>
            <div class="row">
                <center><h4><?=esc(ucwords($row->class))?></h4></center>
                <table class="table table-hover table-striped table-porder">
                    <tr>
                        <th>Create By:</th><th class="font-weight-normal"><?=esc($row->user->firstname)?> <?=esc($row->user->lastname)?></th>
                        <th>Date Create:</th><th class="font-weight-normal"><?=esc(get_date($row->date))?></th>
                    </tr>
                </table>

            </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link <?=$page_tab=='teachers'?'active':'';?> " href="<?=ROOT?>/single_class/<?=$row->class_id?>?tab=teachers">Teachers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?=$page_tab=='students'?'active':'';?> " href="<?=ROOT?>/single_class/<?=$row->class_id?>?tab=students">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?=$page_tab=='tests'?'active':'';?> " href="<?=ROOT?>/single_class/<?=$row->class_id?>?tab=tests">Tests</a>
                    </li>
                </ul>
                <?php

                switch ($page_tab) {
                    case 'teachers':
                        // code...
                        include(views_path('class-tab-teachers'));

                        break;

                    case 'students':
                        // code...
                        include(views_path('class-tab-students'));

                        break;

                    case 'tests':
                        // code...
                        include(views_path('class-tab-tests'));

                        break;

                    case 'test-add':
                        // code...
                        include(views_path('class-tab-test-add'));

                        break;

                    case 'test-edit':
                        // code...
                        include(views_path('class-tab-test-edit'));

                        break;

                    case 'test-delete':
                        // code...
                        include(views_path('class-tab-test-delete'));
                        break;

                    case 'teacher-add':
                        // code...
                        include(views_path('class-tab-teachers-add'));

                        break;

                        case 'teacher-remove':
                            // code...
                            include(views_path('class-tab-teachers-remove'));

                        break;

                    case 'student-add':
                        // code...
                        include(views_path('class-tab-students-add'));

                        break;

                    case 'student-remove':
                        // code...
                        include(views_path('class-tab-students-remove'));

                        break;
                    case 'students-add':
                        // code...
                        include(views_path('class-tab-students-add'));

                        break;
                    case 'tests-add':
                        // code...
                        include(views_path('class-tab-tests-add'));

                        break;

                    default:
                        // code...
                        break;
		 			}


		 		?>



        <?php else :?>
            <h4 class="text-center">That class was not found!</h4>
        <?php endif ;?>
        </div>

<?php $this->view('includes/footer'); ?>