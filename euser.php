<?php

include_once 'core/init.php';
	
	$user = new User();
	
	if(!$user->check()){
		Redirect::to('index');
	}
	
	$slug = $user->data()->slug;
		
		if(!preg_match('/admin/i', $slug)) {
			Redirect::to('index');
		}
		
	$b = DB::getInstance()->query('SELECT * FROM users ORDER BY surname')->results();
	
	$c = Input::page('id');
	
	$sql = 'SELECT * FROM users WHERE id ='.$c;
	
	$test = DB::getInstance()->query($sql)->results();
	
	$validation = new Validation();
	
 	if(Input::exists()) {
		$validate = $validation->check(array(
			'name'	=> array(
				'required' => true,
				'min' => 2,
				'max' => 50
				),
			'surname'	=> array(
				'required' => true,
				'min' => 2,
				'max' => 50
				),
			'slug'	=>	array(
				'required' => true
				)
			));
		if($validate->passed()) {
			$update = DB::getInstance()->update('users', $values = array(
					'name' => ucfirst(Input::get('name')), 
					'surname' => ucfirst(Input::get('surname')), 
					'slug' => ucfirst(Input::get('slug'))), $c);

		$path = 'img';
	
		if( ! is_dir($path)) {
			mkdir($path, 0755);
		}
	
		$total = count($_FILES['file']['name']);
		for($i = 0; $i < $total; $i++) {
			$tmp_name  = $_FILES['file']['tmp_name'][$i];
			$file_name = $_FILES['file']['name'][$i];
			$error     = $_FILES['file']['error'][$i];
			$size      = $_FILES['file']['size'][$i];
	
			if($error == 0 && $size != 0) {
				$file_parts = pathinfo($file_name);
				$ext = $file_parts['extension'];
				$new_name = $c.'.'.$ext;
				$dest = $path . '/' . $new_name;
		
				if(move_uploaded_file($tmp_name, $dest)) {
					Input::get('img');				
				}
			}
		}			
				Session::flash('success', 'Uspješno upisano!');
				Redirect::refresh('1');
		}
	}
	
	
	Helper::getHeader('Korisnici', 'header', $user);

?>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">Naziv tvrtke</a>					
				</div>
				<ul class="nav navbar-nav navbar-left">
					<li><a href="dashboard.php"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Korisnici</a>
							<ul class="dropdown-menu">
								<?php 
								
									foreach($b as $val) {
										$name = $val->name;
										$surname = $val->surname;
										$id = $val->id;
										
										echo '<li style="text-transform:capitalize;"><a href="users.php?id='.$id.'">'.$name.' '.$surname.'</a></li>';
									}
									
								?>
							</ul>
					</li>
				</ul>	
				<ul class="nav navbar-nav navbar-right">
					<li class="active" style="text-transform:capitalize;"><a href="#"><?php echo $user->data()->name; ?> <?php echo $user->data()->surname; ?></a></li>
					<li><a href="logout.php">Odjava</a></li>
				</ul>
			</div>
		</nav>
	</div>
	<div class="container">
	<div class="page-header"></div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
		<?php
			include_once 'notifications.php';
		?>	
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Korisnik</h3>
				</div>
				<div class="panel-body">
					<img src="img/<?php echo $c.'.jpg'; ?>" class="img-responsive center-block" style="width:200px;height:200px;margin-bottom:5%;">
					<?php
						foreach($test as $val) {
							$name = $val->name;
							$surname = $val->surname;
							$slug = $val->slug;
						} ?>
					<p class="text-center text-capitalize"><strong>Ime : </strong> <?php echo $name; ?></p>
					<p class="text-center text-capitalize"><strong>Prezime : </strong> <?php echo $surname; ?></p>
					<p class="text-center text-capitalize"><strong>Dozvole : </strong> <?php echo $slug; ?></p>
					<form method="post" enctype="multipart/form-data">
					<div class="form-group <?php echo ($validation->hasError('name')) ? 'has-error' : '' ?>">	
						<label for="name" class="control-label">Ime :</label>
						<input type="text" id="name" name="name" class="form-control" placeholder="Upiši ime" value="<?php echo Input::get('name'); ?>">
						<?php echo ($validation->hasError('name')) ? '<p class="text-danger">' . $validation->hasError('name') . '</p>' : '' ?>
					</div>
					
					<div class="form-group  <?php echo ($validation->hasError('surname')) ? 'has-error' : '' ?>">					
						<label for="surname" class="control-label">Prezime :</label>
						<input type="text" id="surname" name="surname" class="form-control" placeholder="Upiši prezime" value="<?php echo Input::get('surname'); ?>">
						<?php echo ($validation->hasError('surname')) ? '<p class="text-danger">' . $validation->hasError('surname') . '</p>' : '' ?>
					</div>
					
					<div class="form-group  <?php echo ($validation->hasError('slug')) ? 'has-error' : '' ?>">
						<label for="slug" class="control-label">Dozvole :</label>
						&nbsp;<input type="radio" name="slug" id="slug" value="admin"> Admin&nbsp;&nbsp;
						<input type="radio" name="slug" id="slug" value="korisnik"> Korisnik
						<?php echo ($validation->hasError('slug')) ? '<p class="text-danger">' . $validation->hasError('slug') . '</p>' : '' ?>
					</div>	
					
					<div class="form-group">
						<label for="slug" class="control-label">Fotografija :</label>
						<input type="file" name="file[]" id="img" name="img">
					</div>
					
					<button type="submit" class="btn btn-success btn-block">Spremi</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>	
	
<?php
	Helper::getFooter();
?>