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



?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron pb-md-2">
				<h4>Поиск дипломных</h4><br>
					<form class="form-inline" id="formSearch">
						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<div class="input-group-addon">Студент</div>
							<input type="text" class="form-control" id="s1" placeholder="ФИО">
							<input type="hidden" id="s1h">
						</div>

						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<div class="input-group-addon">Студент</div>
							<input type="text" class="form-control" id="s2" placeholder="ФИО">
							<input type="hidden" id="s2h">
						</div>

						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<input type="submit" class="form-control" value="поиск">
						</div>
					</form>

				<table class="table table-stripped">
					<thead>
						<tr>
							<th>Студент</th>
							<th>Студент</th>
							<th class="right">%</th>
						</tr>
					</thead>
					<tbody id="tableDiplomas"></tbody>
				</table>

				<nav aria-label="Diplomas search results">
					<ul id="diplomasPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first disabled" id="liFirst">
							<a class="page-link first" href="#" aria-label="Previous" 
									data-page="1" id="aFirst">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link" id="pageInfo">1/1</span>
						</li>
						<li class="page-item last disabled" id="liLast">
							<a class="page-link last" href="#" aria-label="Next" 
									data-page="2" id="aLast">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>


<script>


$(document).ready(() => {

	var app = new AP;

	var formSearch = app.getJQNode('#formSearch'),
			tableDiplomas = app.getNode('#tableDiplomas'),
			liFirst = app.getJQNode('#liFirst'),
			aFirst = app.getJQNode('#aFirst'),
			pageInfo = app.getNode('#pageInfo'),
			liLast = app.getJQNode('#liLast'),
			aLast = app.getJQNode('#aLast');



	search();
	formSearch.on('submit', (e) => {
		e.preventDefault();

		search();
	});

	aFirst.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;

		search(p);
	});

	aLast.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;

		search(p);
	});


	function search(page=1) {
		var q = {migration: 'procedure', formData: {
			by: 'str',
			s1: $('#s1').val(),
			s2: $('#s2').val()
		}, limit: page};

		app.ajax('getter', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Can`t get diploma list: '+d.data, 'error');
				console.log(d);
				return;
			}

			// console.log(d);
			setPagin(page, d.info.count);

			var html = '<option value="0">-</option>';
			var rows = '';

			for (var i = 0; i < d.data.length; i++) {
				rows += '<tr>\
									<td>'+d.data[i].s1+'</td>\
									<td>'+d.data[i].s2+'</td>\
									<td class="right">'+d.data[i].percent+'</td>\
								</tr>'; 
			}

			tableDiplomas.innerHTML = rows;
		});
	}


	function setPagin(page, count) {
		if (page <= 1) {
			liFirst.addClass('disabled');
			aFirst.attr('data-page', 1);
		} else {
			liFirst.removeClass('disabled');
			aFirst.attr('data-page', parseInt(page)-1);
		}

		pageInfo.innerText = page+'/'+count;

		if (page >= count) {
			liLast.addClass('disabled');
			aLast.attr('data-page', count);
		} else {
			liLast.removeClass('disabled');
			aLast.attr('data-page', parseInt(page)+1);
		}
	}


});

</script>

<?php

require_once 'access/static/footer.php';
