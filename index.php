<?php

	include_once 'core/init.php';
	
	$user = new User();
	
	$validation = new Validation();
	
	if(Input::exists()) {
		if(Token::check(Input::get('token'))){
			$validate = $validation->check(array(				
				'email'	=> array(
					'required' => true,
				),
				'password' => array(
					'required' => true,
				),
			));
			
			if($validate->passed()) {
				
				$remember = (bool)Input::get('remember');
				$user = $user->login(Input::get('email'), Input::get('password'), $remember);
				
				if($user === 'NOEMAIL'){
					Session::flash('danger', 'Krivi email! Probajte ponovo!.');
					Redirect::to('index');
				} else if($user){
					Redirect::to('dashboard');
				} else {
					Session::flash('danger', 'Nešto je krivo! Probajte ponovo!.');
					Redirect::to('index');
				} 				
			}		
		} else {
			Session::flash('danger', 'Krivi CSRF token!');
			Redirect::to(404);
		}	
	}
	
	Helper::getHeader('Prijava', 'header', $user);
	
?>	

<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4" style="margin-top:15%;">
		<?php
			include_once 'notifications.php';
		?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Prijava</h3>
				</div>
				<div class="panel-body">
					<form method="post">
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
							<div class="form-group <?php echo ($validation->hasError('email')) ? 'has-error' : '' ?>">
								<label for="email" class="control-label">Email*</label>
								<input type="text" name="email" class="form-control" id="email" placeholder="Upišite email" value="<?php echo Input::get('email'); ?>">
								<?php echo ($validation->hasError('email')) ? '<p class="text-danger">' . $validation->hasError('email') . '</p>' : '' ?>
							</div>
							<div class="form-group <?php echo ($validation->hasError('password')) ? 'has-error' : '' ?>">
								<label for="password" class="control-label">Lozinka*</label>
								<input type="password" name="password" class="form-control" id="password" placeholder="Upišite lozinku" value="">
								<?php echo ($validation->hasError('password')) ? '<p class="text-danger">' . $validation->hasError('password') . '</p>' : '' ?>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success btn-lg btn-block">Prijava</button>
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