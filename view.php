<?php

session_start();
ini_set('display_errors', 1);

require_once 'engine/inc/conf.php';
require_once 'engine/inc/db.php';


$protocol = ($_SERVER['REQUEST_SCHEME'] == 'http') ? 'http://' : 'https://';
$url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$url = parse_url($url);



require_once 'access/static/head.php';
require_once 'access/static/header.php';


if (isset($_GET['s1']) && $_GET['s1']
		&& isset($_GET['s2']) && $_GET['s2']) {

	$s1 = (int)$_GET['s1'];
	$s2 = (int)$_GET['s2'];
}


?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="jumbotron">
				<h4 id="s1">Диплом: Гезалов Бахрам И.</h4><br>
				<div id="d1"></div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="jumbotron">
				<h4 id="s2">Диплом: Хадзиев Герман А.</h4><br>
				<div id="d2"></div>
			</div>
		</div>
	</div>
</div>


<script>

$(document).ready(() => {

	var app = new AP;

	var s1 = app.getNode('#s1'),
			d1 = app.getNode('#d1'),
			s2 = app.getNode('#s2'),
			d2 = app.getNode('#d2');



	getDiplomas();


	function getDiplomas() {
		var q = {migration: 'procedure', formData: {
			by: 'id',
			s1: <?=$s1; ?>,
			s2: <?=$s2; ?>
		}};

		app.ajax('getter', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Can`t get diploma list: '+d.data, 'error');
				console.log(d);
				return;
			}

			console.log(d);

			s1.innerText = d.data[0].s1;
			d1.innerHTML = d.data[0].d1;
			s2.innerText = d.data[0].s2;
			d2.innerHTML = d.data[0].d2;
		});
	}

});

</script>

<?php

require_once 'access/static/footer.php';



