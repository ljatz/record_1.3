<?php

	include_once 'core/init.php';
	
	$user = new User();
		
		if(!$user->check()){
			Redirect::to('index');
		}
		
		$objects = DB::getInstance()->query('SELECT * FROM objects ORDER BY name')->results();  

		$users = DB::getInstance()->query('SELECT * FROM users ORDER BY surname')->results(); 
		
		$id = Input::page('id'); 
		
		$slug = $user->data()->slug; 
		
	Helper::getHeader('Naziv glavne lokacije', 'header', $user);
	
?>	
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Naziv tvrtke</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" style="<?php if(!preg_match('/Admin/i', $slug)) { echo 'display:none;';} ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Korisnici</a>
							<ul class="dropdown-menu">
								<?php 				
									foreach($users as $val) {
										$name = $val->name;
										$surname = $val->surname;
										$id = $val->id;
										
										echo '<li style="text-transform:capitalize;"><a href="users.php?id='.$id.'">'.$name.' '.$surname.'</a></li>';
									} 
								?>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Objekti</a>
							<ul class="dropdown-menu">
								<?php 				
									foreach($objects as $val) {
										$name = $val->name;
										$id = $val->id;
										
										echo '<li style="text-transform:capitalize;"><a href="table.php?id='.$id.'">'.$name.'</a></li>';
									}  
								?>
							</ul>
						</li>
					</ul>
				<ul class="nav navbar-nav">
					<li><a href="register.php" style="<?php if(!preg_match('/Admin/i', $slug)) { echo 'display:none;';} ?>">Novi korisnik</a></li>
					<li><a href="nobject.php" style="<?php if(!preg_match('/Admin/i', $slug)) { echo 'display:none;';} ?>">Novi objekt</a></li>
				</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="active"><a href="#"><?php echo $user->data()->name.' '.$user->data()->surname; ?></a></li>
						<li><a href="logout.php">Odjava</a></li>
					</ul>
				</div>
			</div>		
		</nav>
		
		<div class="container">
			<img src="" alt="">
	
<?php
	Helper::getFooter();
?>