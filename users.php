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
	
	foreach($test as $val) {
		$name = $val->name;
		$surname = $val->surname;
		$slug = $val->slug;
	}

	if(Input::exists()) {
		$del = 'DELETE FROM users WHERE id ='.$c;
		$delete = DB::getInstance()->query($del)->results();
		Session::flash('success', 'Uspješno ste obrisali korisnika!');
		Redirect::refresh('1');
	} 

	Helper::getHeader('Korisnici', 'header', $user);

?>

		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">Kontrolna ploča</a>					
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
					<img src="img/<?php echo $c.'.jpg'; ?>" alt="Djelatnik" class="img-responsive center-block" style="width:200px;height:200px;margin-bottom:5%;">
					<?php
						foreach($test as $val) {
							$name = $val->name;
							$surname = $val->surname;
							$slug = $val->slug;
						} ?>
					<p class="text-center text-capitalize"><strong>Ime : </strong> <?php echo $name; ?></p>
					<p class="text-center text-capitalize"><strong>Prezime : </strong> <?php echo $surname; ?></p>
					<p class="text-center text-capitalize"><strong>Dozvole : </strong> <?php echo $slug; ?></p>
					<form method="post">
						<a href="<?php echo 'euser.php?id='.$c; ?>" class="btn btn-success btn-block" role="button">Uredi</a>
						<button type="submit" class="btn btn-danger btn-block" name="del">Obri&#353;i</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
<?php
	Helper::getFooter();
?>