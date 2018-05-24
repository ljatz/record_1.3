<?php

include_once 'core/init.php';
	
	$user = new User();
	
	if(!$user->check()) {
		Redirect::to('index');
	}

	$slug = $user->data()->slug;
	
	$id = Input::page('id');
	
	$sql = 'SELECT * FROM objects WHERE id ='.$id;
	
	$test = DB::getInstance()->query($sql)->results();
	
	foreach($test as $val) {
		$name = $val->name;
		$address = $val->address;
		$id = $val->id;
	}
	
	$loc = DB::getInstance()->query('SELECT * FROM unos WHERE id_lokacija = '.$id)->results();

	$qpns = 'SELECT * FROM unos WHERE stanje = "Iznos" AND NOT razlog = "Rashod" AND id_lokacija ='.$id;
	
	$pns  = DB::getInstance()->query($qpns)->results();
	
	if(Input::exists()) {
		$del = 'DELETE FROM objects WHERE id ='.$id;
		$delete = DB::getInstance()->query($del)->results();
		Session::flash('success', 'Uspješno ste obrisali tablicu!');
		Redirect::refresh('1');
	}

	Helper::getHeader('Pregled nerješenih stavki', 'header', $user);
	
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
					<a class="navbar-brand" href="#"><?php echo (!empty($name)) ? $name : ''; ?></a>
				</div>
				<ul class="nav navbar-nav navbar-left">
					<li><a href="<?php echo 'table.php?id='.$id; ?>">Tablica</a></li>
					<li class="active"><a href="#">Pregled nerješenih stavki</a></li>
				</ul>	
				<ul class="nav navbar-nav navbar-right">
					<li class="active" style="text-transform:capitalize;"><a href="#"><?php echo $user->data()->name; ?> <?php echo $user->data()->surname; ?></a></li>
					<li><a href="logout.php">Odjava</a></li>
				</ul>
			</div>		
		</nav>	
	<div class="container">
		<div class="page-header">
			<?php include_once 'notifications.php';
		?>
		</div>
	<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">	
					<table class="table table-striped" > 
								<tr style="font-weight:bold;">
									<td>Id</td>
									<td>Stanje</td>
									<td>Naziv</td>
									<td>Inv. br.</td>
									<td>Revers</td>
									<td>Razlog</td>
									<td>Bilješka</td>
									<td>Datum</td>
									<td>Upisao</td>
									<td>Lokacija</td>
								</tr>
					<?php foreach($pns as $val) { 
						
						echo '
							<tr>
								<td>'.$val->id .'</td>
								<td>'.$val->stanje .'</td>
								<td>'.$val->naziv .'</td>
								<td>'.$val->inventarni_broj .'</td>
								<td>'.$val->revers .'</td>
								<td>'.$val->razlog .'</td>
								<td>'.$val->note .'</td>
								<td>'.$val->kreiran .'</td>
								<td>'.$val->upisao .'</td>
								<td>'.$val->lokacija .'</td>
							</tr>';
					} ?> 
					</table>
				</div>
			</div>
		</div>

<?php
	Helper::getFooter();
?>