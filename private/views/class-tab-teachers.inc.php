
<nav class="navbar navbar-light bg-light">
    <form class="form-inline">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i>&nbsp</span>
            </div>
            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
        </div>
    </form>
    <?php if(Auth::access('reception')):?>
    <div>

        <a href="<?=ROOT?>/single_class/teacherremove/<?=$row->class_id?>?select=true">
            <button class="btn btn-sm btn-outline-danger"><i class="fa fa-minus"></i>Remove</button>
        </a>
        <a href="<?=ROOT?>/single_class/teacheradd/<?=$row->class_id?>?select=true">
            <button class="btn btn-sm btn-outline-success"><i class="fa fa-plus"></i>Add New</button>
        </a>

    </div>
    <?php endif;?>
</nav>

<div class="card-group justify-content-center">
	<?php if(is_array($teachers)):?>
		<?php foreach($teachers as $teacher):?>

			<?php
				$row = $teacher->user;
				include(views_path('user'));

			?>
		<?php endforeach;?>
	<?php else:?>
		<center><h4 class="pt-4">No teachers were found in this class</h4></center>
	<?php endif;?>
 </div>
 <?php $pager->display()?>