<?php

	include_once 'core/init.php';
	
	$user = new User();
	
	if(!$user->check()) {
		Redirect::to('index');
	} 
	
	$validation = new Validation();
	
		if(Input::exists()) {
			$validate = $validation->check(array(
				'stanje'	=> array(
					'required' => true
				),
				'naziv'	=> array(
					'required' => true
				),
				'inventarni_broj'	=> array(
					'required' => true,
					'num' => true,
					'max' => 10
				),
				'revers' => array(
					'required' => true,
					'max' => 10
				),
				'razlog' => array(
					'required' => true
				),
				'note' => array(
					'required' => true,
					'max' => 50
				)));
				
			if($validate->passed()){
				$values = array('stanje' => Input::get('stanje'), 
					'naziv' => ucfirst(Input::get('naziv')), 
					'inventarni_broj' => Input::get('inventarni_broj'),
					'revers' => Input::get('revers'),
					'razlog' => ucfirst(Input::get('razlog')),
					'note' => ucfirst(Input::get('note')),
					'kreiran' => Input::get('kreiran'),
					'upisao' => ucfirst(Input::get('upisao')),
					'lokacija' => ucfirst(Input::get('lokacija')),
					'id_lokacija' => Input::get('id_lokacija'));
					
					$stanje = $values['stanje'];
					$razlog = $values['razlog'];
					
					if($stanje === 'Unos' && $razlog === 'Trajno korištenje' || $stanje === 'Unos' && $razlog === 'Povrat') {
						DB::getInstance()->insert('unos', $values);	
						Session::flash('success', "Uspješno upisano!");
					} else if($stanje === 'Iznos' && $razlog === 'Rashod' || $stanje === 'Iznos' && $razlog === 'Popravak' || $stanje === 'Iznos' && $razlog === 'Preseljenje') {
						DB::getInstance()->insert('unos', $values);	
						Session::flash('success', "Uspješno upisano!");
					} else {
						Session::flash('danger', "Nešto nije u redu!");
					}					
			}
		}	
	
	$slug = $user->data()->slug;
	$id = $user->data()->id;
	
	$c = Input::page('id');	
	
	$sql = 'SELECT * FROM objects WHERE id ='.$c;
	
	$test = DB::getInstance()->query($sql)->results();
	
	foreach($test as $val) {
		$name = $val->name;
		$address = $val->address;
		$id = $val->id;
	}	
	
	Helper::getHeader('Evidencija unosa i iznosa opreme', 'header', $user);
	
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
					<li <?php if(!preg_match('/admin/i', $slug)) {
							echo 'style="display:none;"';}?>><a href="dashboard.php"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
				</ul>	
				<ul class="nav navbar-nav">
					<li><?php echo '<a href="table.php?id='.$id.'">'; ?>Tablica</a></li>
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
		<form method="post">
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<div class="form-group <?php echo ($validation->hasError('stanje')) ? 'has-error' : '' ?>">
				<label for="stanje" class="control-label">Stanje* </label>
						&nbsp;<input type="radio" name="stanje" id="stanje" value="Unos" <?php echo (Input::get('stanje') === 'Unos') ? 'checked="checked"' : ''; ?>> Unos&nbsp;&nbsp;					
						<input type="radio" name="stanje" id="stanje" value="Iznos" <?php echo (Input::get('stanje') === 'Iznos') ? 'checked="checked"' : ''; ?>> Iznos
						<?php echo ($validation->hasError('stanje')) ? '<p class="text-danger">' . $validation->hasError('stanje') . '</p>' : '' ?>
			</div>
			<div class="form-group <?php echo ($validation->hasError('naziv')) ? 'has-error' : '' ?>">
				<label for="naziv" class="control-label">Naziv opreme* </label>
					<input type="text" id="naziv" name="naziv" class="form-control" placeholder="Upiši naziv" value="<?php echo Input::get('naziv'); ?>">
					<?php echo ($validation->hasError('naziv')) ? '<p class="text-danger">' . $validation->hasError('naziv') . '</p>' : '' ?>
			</div>
			
			<div class="form-group <?php echo ($validation->hasError('inventarni_broj')) ? 'has-error' : '' ?>">
				<label for="inventarni_broj" class="control-label">Inventarni broj* </label>
					<input type="text" id="inventarni_broj" name="inventarni_broj" class="form-control" placeholder="Upiši inventarni broj" value="<?php echo Input::get('inventarni_broj'); ?>">
					<?php echo ($validation->hasError('inventarni_broj')) ? '<p class="text-danger">' . $validation->hasError('inventarni_broj') . '</p>' : '' ?>
			</div>
			<div class="form-group <?php echo ($validation->hasError('revers')) ? 'has-error' : '' ?>">
				<label for="revers" class="control-label">Broj reversa* </label>
					<input type="text" id="revers" name="revers" class="form-control" placeholder="Upiši broj reversa" value="<?php echo Input::get('revers'); ?>">
					<?php echo ($validation->hasError('revers')) ? '<p class="text-danger">' . $validation->hasError('revers') . '</p>' : '' ?>
			</div>
			<div class="form-group <?php echo ($validation->hasError('razlog')) ? 'has-error' : '' ?>">
				<label for="razlog" class="control-label">Razlog* </label>
					<select class="form-control" id="razlog" name="razlog" id="razlog">
						<option value="Rashod" <?php echo (Input::get('razlog') === 'Rashod') ? 'checked="checked"' : ''; ?>>Rashod</option>
						<option value="Popravak" <?php echo (Input::get('razlog') === 'Popravak') ? 'checked="checked"' : ''; ?>>Popravak</option>
						<option value="Preseljenje" <?php echo (Input::get('razlog') === 'Preseljenje') ? 'checked="checked"' : ''; ?>>Preseljenje</option>
						<option value="Trajno korištenje" <?php echo (Input::get('razlog') === 'Trajno korištenje') ? 'checked="checked"' : ''; ?>>Trajno korištenje</option>
						<option value="Povrat" <?php echo (Input::get('razlog') === 'Povrat') ? 'checked="checked"' : ''; ?>>Povrat</option>
					</select>
					<?php echo ($validation->hasError('razlog')) ? '<p class="text-danger">' . $validation->hasError('razlog') . '</p>' : '' ?>
			</div>
			<div class="form-group <?php echo ($validation->hasError('note')) ? 'has-error' : '' ?>">
				<label for="">Bilješka</label>
					<input type="text" id="note" name="note" class="form-control" placeholder="Upiši bilješku" value="<?php echo Input::get('note'); ?>">
					<?php echo ($validation->hasError('note')) ? '<p class="text-danger">' . $validation->hasError('note') . '</p>' : '' ?>
			</div>
			<input type="hidden" name="kreiran" value="<?php echo date('j.n.Y G:i');?>">
			<input type="hidden" name="upisao" value="<?php echo $user->data()->name.' '.$user->data()->surname; ?>">
			<input type="hidden" name="lokacija" value="<?php echo ucfirst($name); ?>">
			<input type="hidden" name="id_lokacija" value="<?php echo $id; ?>">
			<button type="submit" class="btn btn-success btn-block">Dodaj</button>
		</form>
	</div>
</div>

<?php
	Helper::getFooter();
?>