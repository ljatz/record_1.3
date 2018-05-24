<?php

	include_once 'core/init.php';

	$user = new User();
		
	$validation = new Validation();
	
	if(Input::exists()) {
		if(Token::check(Input::get('token'))){
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
				'email'	=> array(
					'required' => true,
					'unique' => 'users',
					'must_have'	=> false // opcionalno 
				),
				'password' => array(
					'required' => true,
					'min' => 8
				),
				'password_again' => array(
					'required' => true,
					'matches' => 'password'
				),
				'slug'			=>	array(
					'required'	=>	true
			)));
			
			if($validate->passed()) {
				
				$salt = Hash::salt(32);
				
				try {
					
					$user->create(array(
						'name' 		=> ucfirst(Input::get('name')),
						'surname'	=> ucfirst(Input::get('surname')),
						'slug'		=> ucfirst(Input::get('slug')),
						'email'		=> Input::get('email'),
						'password'	=> Hash::make(Input::get('password'), $salt),
						'salt'		=> $salt
						
					));
					
				} catch(Exception $e) {
					
					Session::flash('danger', $e->getMessage());
					exit();
				}
				Redirect::to('dashboard');
			}		
		} else {
			Session::flash('danger', 'Krivi CSRF token!');
			Redirect::to(404);
		}
	}
	
	$once = DB::getInstance()->query('SELECT * FROM users')->count();
	
	if($once !== 0) {
		Redirect::to('login');
	}
	
	Helper::getHeader('Master registracija', 'header', $user);
	
?>	

<div class="container">
	<div class="col-md-6 col-md-offset-3" style="margin-top:10%">
	
	<?php
			include_once 'notifications.php';
		?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Master registracija</h3>
				</div>
				<div class="panel-body">
					<form method="post">
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					<div class="form-group <?php echo ($validation->hasError('name')) ? 'has-error' : '' ?>">
						<label for="name" class="control-label">Ime*</label>
						<input type="text" name="name" class="form-control" id="name" placeholder="Upišite ime" value="<?php echo Input::get('name'); ?>" autocomplete="off">
						<?php echo ($validation->hasError('name')) ? '<p class="text-danger">' . $validation->hasError('name') . '</p>' : '' ?>
					</div>
					<div class="form-group <?php echo ($validation->hasError('surname')) ? 'has-error' : '' ?>">
						<label for="name" class="control-label">Prezime*</label>
						<input type="text" name="surname" class="form-control" id="surname" placeholder="Upišite prezime" value="<?php echo Input::get('surname'); ?>" autocomplete="off">
						<?php echo ($validation->hasError('surname')) ? '<p class="text-danger">' . $validation->hasError('surname') . '</p>' : '' ?>
					</div>
					<div class="form-group <?php echo ($validation->hasError('email')) ? 'has-error' : '' ?>">
						<label for="email" class="control-label">Email*</label>
						<input type="text" name="email" class="form-control" id="email" placeholder="Upišite email" value="<?php echo Input::get('email'); ?>"><?php echo ($validation->hasError('email')) ? '<p class="text-danger">' . $validation->hasError('email') . '</p>' : '' ?>
					</div>
					<div class="form-group <?php echo ($validation->hasError('password')) ? 'has-error' : '' ?>">
						<label for="password" class="control-label">Lozinka*</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Upišite lozinku" value="">
						<?php echo ($validation->hasError('password')) ? '<p class="text-danger">' . $validation->hasError('password') . '</p>' : '' ?>
					</div>
					<div class="form-group <?php echo ($validation->hasError('password_again')) ? 'has-error' : '' ?>">
						<label for="password_again" class="control-label">Potvrda lozinke*</label>
						<input type="password" name="password_again" class="form-control" id="password_again" placeholder="Upišite ponovo lozinku" value="">
						<?php echo ($validation->hasError('password_again')) ? '<p class="text-danger">' . $validation->hasError('password_again') . '</p>' : '' ?>
					</div>
					<div class="form-group">
						<input type="hidden" name="slug" id="slug" value="Admin">
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-lg btn-block">Registracija</button>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>	
	
