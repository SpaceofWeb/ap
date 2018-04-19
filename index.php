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



// Пагинация
$limit = [];

foreach (['t'=> 0] as $key => $val) {
	$limit[$key] = (isset($_GET['p'][$key])) ? ($_GET['p'][$key]-1)*$cfg['rowsPerPage'] : 0;
	$pag[$key] = (isset($_GET['p'][$key])) ? $_GET['p'][$key] : 1;
}


?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron pb-md-2">
				<h4>Search diplomas</h4><br>
					<form class="form-inline" id="formSearch">
						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<div class="input-group-addon">Student1</div>
							<input type="text" class="form-control" id="s1" placeholder="FML">
							<input type="hidden" id="s1h">
						</div>

						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<div class="input-group-addon">Student2</div>
							<input type="text" class="form-control" id="s2" placeholder="FML">
							<input type="hidden" id="s2h">
						</div>

						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<input type="submit" class="form-control" value="search">
						</div>
					</form>

				<table class="table table-stripped">
					<thead>
						<tr>
							<th>Student</th>
							<th>Student2</th>
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

<link href="access/styles/css/jquery-ui.css" rel="stylesheet">
<script src="access/styles/js/jquery-ui.js"></script>

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



	// $('#s1').autocomplete({
	// 	source: 'engine/ajax/getter.php',
	// 	select: (event, ui) => {
	// 		console.log(event, ui);
	// 		$('#tS1h').val(ui.item.id);
	// 		getStudents(ui.item.id, 'diplomas');
	// 	}
	// });

	// $('#s2').autocomplete({
	// 	source: 'engine/ajax/getOptions.php',
	// 	select: (event, ui) => {
	// 		$('#tS2h').val(ui.item.id);
	// 		getStudents(ui.item.id, 'diplomas');
	// 	}
	// });


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



// $(document).ready(() => {

// var rowsPerPage = <?=$cfg['rowsPerPage']; ?>,
// 	diplomas = $('#diplomas'),
// 	topDiplomas = $('#topDiplomas');


// // Автодополнение для поиска
// // $('#search').autocomplete({
// // 	source: 'engine/ajax/getOptions.php',
// // 	select: (event, ui) => {
// // 		$('#studentHidden').val(ui.item.id);
// // 		getStudents(ui.item.id, 'diplomas');
// // 	}
// // });

// $('#tS1').autocomplete({
// 	source: 'engine/ajax/getOptions.php',
// 	select: (event, ui) => {
// 		$('#tS1h').val(ui.item.id);
// 		getStudents(ui.item.id, 'diplomas');
// 	}
// });

// $('#tS2').autocomplete({
// 	source: 'engine/ajax/getOptions.php',
// 	select: (event, ui) => {
// 		$('#tS2h').val(ui.item.id);
// 		getStudents(ui.item.id, 'diplomas');
// 	}
// });


// // Пагинация дипломных по студенту
// $('#diplomasPag a').on('click', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.d = e.currentTarget.dataset.page;

// 	search('', 'diplomas', p, (data, count) => {
// 		var html = '';

// 		for (var i in data) {
// 			html += '<tr>\
// 						<td>'+data[i].name+'</td>\
// 						<td class="right">'+data[i].percent+'</td>\
// 					</tr>';
// 		}

// 		diplomas.val('');
// 		diplomas.html(html);

// 		setPagination(p.d, count, 'diplomas');
// 	});
// });


// // Пагинация дипломных
// $('#topDiplomasPag a').on('click', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.t = e.currentTarget.dataset.page;

// 	search('', 'topDiplomas', p, (data, count) => {
// 		var html = '';

// 		for (var i in data) {
// 			html += '<tr>\
// 						<td>'+data[i].name+'</td>\
// 						<td>'+data[i].name2+'</td>\
// 						<td class="right">'+data[i].percent+'</td>\
// 					</tr>';
// 		}

// 		topDiplomas.val('');
// 		topDiplomas.html(html);

// 		setPagination(p.t, count, 'topDiplomas');

// 		history.pushState({}, '', '?'+toQueryString(p));
// 	});
// });





// // Выбрать список студентов
// function getStudents(id, instance) {
// 	$.ajax({
// 		url: 'engine/ajax/searchMain.php',
// 		type: 'POST',
// 		data: {student: $('#tS1h').val(), student2: $('#tS2h').val(), instance: instance},
// 		success: (data) => {
// 			try {
// 				data = JSON.parse(data);
// 			} catch(e) {}

// 			// $('#tS1h').val('');
// 			// $('#tS2h').val('');

// 			var html = '';
// 			for (var i in data.data) {
// 				html += '<tr>\
// 							<td>'+data.data[i].name1+'</td>\
// 							<td>'+data.data[i].name2+'</td>\
// 							<td class="right">'+data.data[i].percent+'</td>\
// 						</tr>';
// 			}

// 			diplomas.val('');
// 			diplomas.html(html);

// 			setPagination(1, data.count, 'diplomas');
// 		}
// 	});
// }


// // Поиск
// function search(s, instance, pagin, cb) {
// 	$.ajax({
// 		url: 'engine/ajax/searchMain.php',
// 		type: 'POST',
// 		cache: false,
// 		data: {'student': $('#studentHidden').val(), 'instance': instance, 'pagin': pagin},
// 		success: (data) => {
// 			try {
// 				data = JSON.parse(data);
// 			} catch(e) {}

// 			// if error
// 			if (data.err) {
// 				$.notify(data.err, 'error');
// 				return;
// 			}

// 			cb(data.data, data.count);
// 		}
// 	});
// }


// // Создание и установка пагинации
// function setPagination(current, count, instance) {
// 	current = (parseInt(current)) ? parseInt(current) : 1;
// 	count = parseInt(count);

// 	var all = Math.ceil(count/rowsPerPage);
// 	all = (all == 0) ? 1 : all;

// 	if (current <= 1) {
// 		$('#'+instance+'Pag').find('li.first').addClass('disabled');
// 		$('#'+instance+'Pag').find('li.first>a').attr('data-page', 1);
// 	} else {
// 		$('#'+instance+'Pag').find('li.first').removeClass('disabled');
// 		$('#'+instance+'Pag').find('li.first>a').attr('data-page', current-1);
// 	}

// 	$('#'+instance+'Pag').find('li>span').text(current+'/'+all);

// 	if (current >= all) {
// 		$('#'+instance+'Pag').find('li.last').addClass('disabled');
// 		$('#'+instance+'Pag').find('li.last>a').attr('data-page', count);
// 	} else {
// 		$('#'+instance+'Pag').find('li.last').removeClass('disabled');
// 		$('#'+instance+'Pag').find('li.last>a').attr('data-page', current+1);
// 	}

// }


// // Выбрать индекс страницы для пагинации
// function getPage() {
// 	var s = window.location.search.substring(1).split('&');
// 	var p = {};

// 	for (i in s) {
// 		if (/p\[(\w+)\]=(\d*)/.test(s[i])) {
// 			var m = /p\[(\w+)\]=(\d*)/.exec(s[i]);
// 			p[m[1]] = m[2];
// 		}
// 	}

// 	return p;
// }


// // Массив в строку запроса
// function toQueryString(a) {
// 	var out = [];
// 	for(key in a) {
// 		out.push('p[' + key + ']=' + encodeURIComponent(a[key]));
// 	}

// 	return out.join('&');
// }


// });

</script>

<?php

require_once 'access/static/footer.php';
