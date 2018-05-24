<?php

include_once 'core/init.php';
	
	$user = new User();
	
	if(!$user->check()) {
		Redirect::to('login');
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
	
	if(Input::exists()) {
		
		$check = Input::get('check');
		
		if(!empty($check)) {
			$check = implode(' ',$check);
			$del = 'DELETE FROM unos WHERE id ='.$check;
			$delete = DB::getInstance()->query($del)->results();
			Session::flash('success', 'Uspješno ste obrisali stavku!');
			Redirect::refresh('1');
		} else {
			Session::flash('warning', 'Niste odabrali stavku!');
		}
	}

	Helper::getHeader('Tablica upisa opreme', 'header', $user);
	
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
					<li style="<?php if(!preg_match('/Admin/i', $slug)) { echo 'display:none;';} ?>"><a href="dashboard.php"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
					<li><a href="<?php echo 'unos.php?id='.$id; ?>">Upis</a></li>
					<li class="active"><a href="#">Tablica</a></li>	
					<li><a href="<?php echo 'pns.php?id='.$id; ?>">Pregled nerješenih stavki</a></li>
					<li style="<?php if(!preg_match('/admin/i', $slug)) { echo 'display:none;';} ?>"><a href="<?php echo 'die.php?id='.$id; ?>">Obriši objekt</a></li>
					<li class="navbar-form navbar-left">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Pretraži po inv. br." id="myInput" onkeyup="myFunction()">
						</div>		
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="active" style="text-transform:capitalize;"><a href="#"><?php echo $user->data()->name; ?> <?php echo $user->data()->surname; ?></a></li>
					<li><a href="logout.php">Odjava</a></li>
				</ul>
			</div>		
		</nav>	
	
	<div class="container">
		<div class="page-header">
		<?php
			include_once 'notifications.php';
		?>
		</div>
	<div class="row">
		<div class="col-md-4" style="margin: 10px 0"></div>
		<div class="col-md-4">
			<form method="post">
				<button type="submit" class="btn btn-danger btn-block" name="del" style="margin:10px 0; <?php if(!preg_match('/admin/i', $slug)) { echo 'display:none;';} ?>">Obri&#353;i stavku</button>
		</div>
		<div class="col-md-4"></div>
			<div class="panel panel-default">
				<div class="panel-body">	
					<table class="table table-striped" id="myTable"> 
								<tr>
									<th style="<?php if(!preg_match('/Admin/i', $slug)) { echo 'display:none;';} ?>"> </th>
									<th>Id</th>
									<th>Stanje</th>
									<th>Naziv</th>
									<th>Inv. br.</th>
									<th>Revers</th>
									<th>Razlog</th>
									<th>Bilješka</th>
									<th>Datum</th>
									<th>Upisao</th>
									<th>Lokacija</th>
								</tr>
							
					<?php foreach($loc as $val) { ?>
	
							<tr>
								<td style="<?php if(!preg_match('/Admin/i', $slug)) { echo 'display:none;';} ?>"><input type="checkbox" value="<?php echo $val->id; ?>" name="check[]" id="check"></td>
								<td><?php echo $val->id; ?></td>
								<td><?php echo $val->stanje; ?></td>
								<td><?php echo $val->naziv; ?></td>
								<td><?php echo $val->inventarni_broj; ?></td>
								<td><?php echo $val->revers; ?></td>
								<td><?php echo $val->razlog; ?></td>
								<td><?php echo $val->note; ?></td>
								<td><?php echo $val->kreiran; ?></td>
								<td><?php echo $val->upisao; ?></td>
								<td><?php echo $val->lokacija; ?></td>
							</tr>
					<?php }; ?>
					</table>
					<script>
						function myFunction() {
							var input, filter, table, tr, td, i;
							input = document.getElementById("myInput");
							filter = input.value.toUpperCase();
							table = document.getElementById("myTable");
							tr = table.getElementsByTagName("tr");
							  for (i = 0; i < tr.length; i++) {
								td = tr[i].getElementsByTagName("td")[4];
								if (td) {
								  if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
									tr[i].style.display = "";
								  } else {
									tr[i].style.display = "none";
								  }
								}       
							  }
							}
					</script>
				</div>
				</form>
			</div>
		</div>
	</div>

<?php
	Helper::getFooter();
?>