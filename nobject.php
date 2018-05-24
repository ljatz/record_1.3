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
	
	$validation = new Validation();
	
	if(Input::exists()) {
		$validate = $validation->check(array(
			'name'	=> array(
				'required' => true,
				'min' => 2,
				'max' => 50,
				'unique' => 'objects'
				),
			'address'	=> array(
				'required' => true,
				'min' => 2,
				'max' => 50,
				'unique' => 'objects'
				)));
	
		if($validate->passed()) {
			$values = array('name' => ucfirst(Input::get('name')), 
				'address' => ucfirst(Input::get('address')));
			DB::getInstance()->insert('objects', $values);	
			Session::flash('success', 'Uspješno upisan novi objekt!');
		}
	}
	
	Helper::getHeader('Novi objekt', 'header', $user);
	
?>	
    

		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">Naziv tvrtke</a>					
				</div>
				<ul class="nav navbar-nav navbar-left">
					<li><a href="dashboard.php"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="active" style="text-transform:capitalize;"><a href="#"><?php echo $user->data()->name.' '.$user->data()->surname; ?></a></li>
					<li><a href="logout.php">Odjava</a></li>
				</ul>
			</div>
		</nav>
<div class="container">
		<div class="page-header"></div>
	<div class="col-md-6 col-md-offset-3" style="margin-top:1.5%">
	
	<?php
			include_once 'notifications.php';
		?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Upis novog objekta</h3>
				</div>
				<div class="panel-body">
					<form method="post">
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					<div class="form-group <?php echo ($validation->hasError('name')) ? 'has-error' : '' ?>">
						<label for="name" class="control-label">Naziv*</label>
						<input type="text" name="name" class="form-control" id="name" placeholder="Upišite naziv objekta" value="<?php echo Input::get('name'); ?>" autocomplete="off">
						<?php echo ($validation->hasError('name')) ? '<p class="text-danger">' . $validation->hasError('name') . '</p>' : '' ?>
					</div>
					<div class="form-group <?php echo ($validation->hasError('address')) ? 'has-error' : '' ?>">
						<label for="address" class="control-label">Adresa*</label>
						<input type="text" name="address" class="form-control" id="address" placeholder="Upišite adresu objekta" value="<?php echo Input::get('address'); ?>" autocomplete="off">
						<?php echo ($validation->hasError('address')) ? '<p class="text-danger">' . $validation->hasError('address') . '</p>' : '' ?>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-lg btn-block">Upis</button>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>	
	
<?php
	Helper::getFooter();
?>