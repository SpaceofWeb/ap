<?php

session_start();
ini_set('display_errors', 1);

require_once 'engine/inc/conf.php';
require_once 'engine/inc/db.php';


// $protocol = ($_SERVER['REQUEST_SCHEME'] == 'http') ? 'http://' : 'https://';
// $url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
// $url = parse_url($url);



require_once 'access/static/head.php';
require_once 'access/static/header.php';



?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="jumbotron pb-md-1">
				<h4>Изменить диплом</h4><br>


				<form id="dForm" class="form-inline mt-2 mt-md-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="dSearch" class="form-control" placeholder="Search">
							</div>
						</div>
					</div>
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Диплом студента</th>
							<th class="right">Действия</th>
						</tr>
					</thead>
					<tbody id="tableDiplomas"></tbody>
<?php

// $q = "SELECT COUNT(D.id) AS count
// 		FROM {$cfg['dbprefix']}_diplomas D
// 		LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id ";

// $q = "SELECT D.id, CONCAT(S.firstName, ' ', SUBSTR(S.middleName, 1, 1), '. ', SUBSTR(S.lastName, 1, 1), '.') AS name
// 		FROM {$cfg['dbprefix']}_diplomas D
// 		LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id
// 		ORDER BY S.firstName, S.middleName, S.lastName
// 		LIMIT ".$limit['d'].','.$cfg['rowsPerPage'];

?>
				</table>
				<nav aria-label="Diplomas search results">
					<ul id="diplomasPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first disabled">
							<a class="page-link first" href="#" aria-label="Previous"
									data-page="1">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">1/1</span>
						</li>
						<li class="page-item last disabled">
							<a class="page-link last" href="#" aria-label="Next"
									data-page="1">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron pb-md-1">
				<h4>Изменить студента</h4><br>


				<form id="sForm" class="form-inline mt-2 mt-md-0 was-validated">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="sSearch" class="form-control is-invalid" placeholder="Search">
							</div>
						</div>
					</div>
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Студент</th>
							<th class="right">Действия</th>
						</tr>
					</thead>
					<tbody id="tableStudents"></tbody>
<!-- id, CONCAT(firstName, ' ', SUBSTR(middleName, 1, 1), '. ', SUBSTR(lastName, 1, 1), '.') AS name -->
<!-- <tr><td colspan="2"><font color="red">Ни одной записи не найдено</font></td></tr> -->
				</table>
				<nav aria-label="Students search results">
					<ul id="studentsPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first disabled">
							<a class="page-link first" href="#" aria-label="Previous"
									data-page="1">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">1/1</span>
						</li>
						<li class="page-item last disabled">
							<a class="page-link last" href="#" aria-label="Next"
									data-page="1">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron pb-md-1">
				<h4>Изменить группу</h4><br>

				<form id="gForm" class="form-inline mt-2 mt-md-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="gSearch" class="form-control" placeholder="Search">
							</div>
						</div>
					</div>
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Группа</th>
							<th class="right">Действия</th>
						</tr>
					</thead>
					<tbody id="tableGroups"></tbody>
				</table>
				<nav aria-label="Groups search results">
					<ul id="groupsPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first disabled">
							<a class="page-link first" href="#" aria-label="Previous"
									data-page="1">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">1/1</span>
						</li>
						<li class="page-item last disabled">
							<a class="page-link last" href="#" aria-label="Next"
									data-page="1">
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







<!-- Модальные окна -->
<div class="modal fade" id="diplomas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Изменить диплом</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" id="formChangeDiploma">
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Студент</span>
						<select class="form-control" name="student"></select>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Год защиты</span>
						<input type="text" class="form-control" name="year">
					</div><br>

					<div class="input-group">
						<input type="file" name="doc" class="custom-file-input1">
						<!-- <span class="custom-file-control"></span> -->
					</div><br>

					<div id="progress" class="progress">
						<div class="progress-bar progress-bar-striped" role="progressbar" style="width:0%"
								aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div><br>
					<input type="hidden" name="id">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div class="modal fade" id="students" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Изменить студента</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST">
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Фамилия</span>
						<input type="text" class="form-control" name="firstName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Имя</span>
						<input type="text" class="form-control" name="middleName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Отчество</span>
						<input type="text" class="form-control" name="lastName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Грппа</span>
						<select class="form-control" name="group"></select>
					</div>
					<input type="hidden" name="id">
					<input type="hidden" name="instance">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Change Group</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST">
				<div class="modal-body">
						<div class="input-group">
							<span class="input-group-addon bgcolor">Название группы</span>
							<input type="hidden" name="id">
							<input type="hidden" name="instance">
							<input type="text" class="form-control" name="group">
						</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>


$(document).ready(() => {

	var app = new AP;

	var tableGroups = app.getNode('#tableGroups'),
			tableStudents = app.getNode('#tableStudents'),
			tableDiplomas = app.getNode('#tableDiplomas');



	getGroups();
	getStudents();
	getDiplomas();


	function getGroups(page=1) {
		var q = {migration: 'groups', order: 'name', limit: page};

		app.ajax('getter', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Can`t get diploma list: '+d.data, 'error');
				console.log(d);
				return;
			}

			// console.log(d);
			// setPagin(page, d.info.count);

			var rows = '';

			for (var i = 0; i < d.data.length; i++) {
				rows += '<tr>\
									<td>'+d.data[i].name+'</td>\
									<td class="right">\
										<button type="button" class="btn btn-warning btn-sm btnChange" data-toggle="modal"\
												data-target="#groups" data-instance="groups" data-id="'+d.data[i].id+'">♻</button>\
									</td>\
								</tr>'; 
			}

			tableGroups.innerHTML = rows;
		}, false);
	}


	function getStudents(page=1) {
		var q = {migration: 'students', order: 'middleName', limit: page};

		app.ajax('getter', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Can`t get diploma list: '+d.data, 'error');
				console.log(d);
				return;
			}

			// console.log(d);
			// setPagin(page, d.info.count);

			var rows = '';

			for (var i = 0; i < d.data.length; i++) {
				rows += '<tr>\
									<td>'+d.data[i].middleName+'</td>\
									<td class="right">\
										<button type="button" class="btn btn-warning btn-sm btnChange" data-toggle="modal"\
												data-target="#groups" data-instance="groups" data-id="'+d.data[i].id+'">♻</button>\
									</td>\
								</tr>'; 
			}

			tableStudents.innerHTML = rows;
		}, false);
	}


	function getDiplomas(page=1) {
		var q = {migration: 'diplomas', order: '', limit: page};

		app.ajax('getter', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Can`t get diploma list: '+d.data, 'error');
				console.log(d);
				return;
			}

			// console.log(d);
			// setPagin(page, d.info.count);

			var rows = '';

			for (var i = 0; i < d.data.length; i++) {
				rows += '<tr>\
									<td>'+d.data[i].text+'</td>\
									<td class="right">\
										<button type="button" class="btn btn-warning btn-sm btnChange" data-toggle="modal"\
												data-target="#groups" data-instance="groups" data-id="'+d.data[i].id+'">♻</button>\
									</td>\
								</tr>'; 
			}

			tableDiplomas.innerHTML = rows;
		}, false);
	}

});








// $(document).ready(() => {


// var rowsPerPage = <?=$cfg['rowsPerPage']; ?>,
// 	dSearch = $('#dSearch'),
// 	sSearch = $('#sSearch'),
// 	gSearch = $('#gSearch'),
// 	tableDiplomas  = $('#tableDiplomas'),
// 	tableStudents  = $('#tableStudents'),
// 	tableGroups    = $('#tableGroups'),
// 	dModalForm = $('#diplomas form'),
// 	sModalForm = $('#students form'),
// 	gModalForm = $('#groups form'),
// 	formChangeDiploma = $('#formChangeDiploma');



// // Поиск при вводе
// $('#dForm').on('input', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.d = 1;

// 	search(dSearch.val(), 'diplomas', p, (data, count) => {
// 		setResults(data, 'diplomas');
// 		setPagination(p.d, count, 'diplomas');

// 		history.pushState({}, '', '?'+toQueryString(p));
// 	});
// });


// $('#sForm').on('input', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.s = 1;

// 	search(sSearch.val(), 'students', p, (data, count) => {
// 		setResults(data, 'students');
// 		setPagination(p.s, count, 'students');

// 		history.pushState({}, '', '?'+toQueryString(p));
// 	});
// });


// $('#gForm').on('input', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.g = 1;

// 	search(gSearch.val(), 'groups', p, (data, count) => {
// 		setResults(data, 'groups');
// 		setPagination(p.g, count, 'groups');

// 		history.pushState({}, '', '?'+toQueryString(p));
// 	});
// });
// // ==========================



// // Переход по пагинациям
// $('#diplomasPag a').on('click', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.d = e.currentTarget.dataset.page;

// 	search(dSearch.val(), 'diplomas', p, (data, count) => {
// 		setResults(data, 'diplomas');
// 		setPagination(p.d, count, 'diplomas');

// 		history.pushState({}, '', '?'+toQueryString(p));
// 	});
// });


// $('#studentsPag a').on('click', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.s = e.currentTarget.dataset.page;

// 	search(sSearch.val(), 'students', p, (data, count) => {
// 		setResults(data, 'students');
// 		setPagination(p.s, count, 'students');

// 		history.pushState({}, '', '?'+toQueryString(p));
// 	});
// });


// $('#groupsPag a').on('click', (e) => {
// 	e.preventDefault();

// 	var p = getPage();
// 	p.g = e.currentTarget.dataset.page;

// 	search(gSearch.val(), 'groups', p, (data, count) => {
// 		setResults(data, 'groups');
// 		setPagination(p.g, count, 'groups');

// 		history.pushState({}, '', '?'+toQueryString(p));
// 	});
// });
// // ==========================




// changeEvents();

// // Инициализация ивентов изменения
// function changeEvents() {
// 	$('.btnChange').off('click');

// 	$('.btnChange').on('click', (e) => {
// 		e.preventDefault();

// 		var id = e.target.dataset.id,
// 			instance = e.target.dataset.instance;

// 		getRow(instance, id, (data) => {
// 			switch(instance) {
// 				case 'diplomas':
// 					dModalForm.find('[name="id"]').val(id);
// 					dModalForm.find('[name="year"]').val(data.year);
// 					dModalForm.find('[name="student"]').html(data.students);
// 					dModalForm.find('[name="instance"]').val('students');
// 				break;
// 				case 'students':
// 					sModalForm.find('[name="id"]').val(id);
// 					sModalForm.find('[name="firstName"]').val(data.firstName);
// 					sModalForm.find('[name="middleName"]').val(data.middleName);
// 					sModalForm.find('[name="lastName"]').val(data.lastName);
// 					sModalForm.find('[name="group"]').html(data.groups);
// 					sModalForm.find('[name="instance"]').val('students');
// 				break;
// 				case 'groups':
// 					gModalForm.find('[name="id"]').val(id);
// 					gModalForm.find('[name="group"]').val(data.name);
// 					gModalForm.find('[name="instance"]').val('groups');
// 				break;
// 			}
// 		});

// 	});
// }



// // Сохранение данных в модальных окнах
// dModalForm.on('submit', (e) => {
// 	e.preventDefault();


// });


// sModalForm.on('submit', (e) => {
// 	e.preventDefault();

// 	setChange('students');
// });


// gModalForm.on('submit', (e) => {
// 	e.preventDefault();

// 	setChange('groups');
// });
// // ==========================


// // Обновить значения после изменения
// function reload(instance) {
// 	var p = getPage();

// 	if (instance == 'diplomas') {

// 		search(dSearch.val(), 'diplomas', p, (data, count) => {
// 			setResults(data, 'diplomas');
// 		});

// 	} else if (instance == 'students') {

// 		search(sSearch.val(), 'students', p, (data, count) => {
// 			setResults(data, 'students');
// 		});

// 	} else if (instance == 'groups') {

// 		search(gSearch.val(), 'groups', p, (data, count) => {
// 			setResults(data, 'groups');
// 		});
// 	}
// }


// // Загрузить дипломную и добавить в базу
// formChangeDiploma.jqUpload({
// 	url: 'engine/ajax/setChange.php',
// 	dataType: 'json',
// 	// dataType: 'text',
// 	allowedTypes: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
// 	extFilter: 'docx',
// 	checkForFile: false,
// 	getData: () => {
// 		return {
// 			id: formChangeDiploma.find('[name="id"]').val(),
// 			year: formChangeDiploma.find('[name="year"]').val(),
// 			student: formChangeDiploma.find('[name="student"]').val(),
// 			instance: 'diplomas'
// 		};
// 	},
// 	onBeforeUpload: function() {
// 		$.notify('Загрузка начата', 'info');
// 	},
// 	onUploadProgress: function(percent) {
// 		$('div.progress-bar').width(percent + '%');
// 	},
// 	onUploadSuccess: function(data) {
// 		try {
// 			data = JSON.parse(data);
// 		} catch(e) {}

// 		console.log("Server Response: \n", data);

// 		if (data.err) {
// 			$.notify(data.err, 'error');
// 			$('div.progress-bar').width('0%');
// 		} else {
// 			$.notify('Дипломная работа успешно сохранена', 'success');
// 			$('div.progress-bar').width('100%');

// 			formChangeDiploma.find('[type="file"]').val('');

// 			setTimeout(() => {
// 				$('div.progress-bar').width('0%');
// 			}, 2000);
// 		}
// 	},
// 	onUploadError: function(message) {
// 		console.log(message);
// 		$.notify('Ошибка загрузки файла: ' + message, 'error');
// 	},
// 	onFileTypeError: function(file) {
// 		$.notify('Файл \'' + file.name + '\' должен быть с расширением ".docx"', 'error');
// 	},
// 	onFallbackMode: function(message) {
// 		$.notify('Браузер не поддерживается: ' + message, 'error');
// 	}
// });




// // Сохранить изменения из модальных окон в базу
// function setChange(instance) {
// 	if (instance == 'students') {
// 		var data = sModalForm.serialize();
// 	} else if (instance == 'groups') {
// 		var data = gModalForm.serialize();
// 	}


// 	$.ajax({
// 		url: 'engine/ajax/setChange.php',
// 		type: 'POST',
// 		cache: false,
// 		data: data,
// 		success: (data) => {
// 			console.log(data);
// 			try {
// 				data = JSON.parse(data);
// 			} catch(e) {}

// 			// if error
// 			if (data.err) {
// 				$.notify(data.err, 'error');
// 			} else {
// 				$.notify(data.success, 'success');
// 			}

// 			reload(data.instance);
// 		}
// 	});
// }


// // Поиск
// function search(s, instance, pagin, cb) {
// 	$.ajax({
// 		url: 'engine/ajax/search.php',
// 		type: 'POST',
// 		cache: false,
// 		data: {'s': s, 'instance': instance, 'pagin': pagin},
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



// // Вывести результаты поиска
// function setResults(data, instance) {
// 	var html = '';

// 	for (var i in data) {
// 		html += '<tr>\
// 					<td>'+data[i].name+'</td>\
// 					<td class="right">\
// 						<button type="button" class="btn btn-warning btn-sm btnChange"\
// 								data-toggle="modal" data-target="#'+instance+'"\
// 								data-instance="'+instance+'" data-id="'+data[i].id+'">♻</button>\
// 					</td>\
// 				</tr>';
// 						// <button type="button" class="btn btn-danger btn-sm btnDelete"\
// 						// 		data-id="'+data[i].id+'">X</button>\
// 	}


// 	switch(instance) {
// 		case 'diplomas':
// 			tableDiplomas.val('');
// 			tableDiplomas.html(html);
// 		break;
// 		case 'students':
// 			tableStudents.val('');
// 			tableStudents.html(html);
// 		break;
// 		case 'groups':
// 			tableGroups.val('');
// 			tableGroups.html(html);
// 		break;
// 	}
// }



// // Создать и установить пагинацию
// function setPagination(current, count, instance) {
// 	current = parseInt(current);
// 	count = parseInt(count);

// 	var all = Math.ceil(count/rowsPerPage);
// 	all = (all == 0) ? 1 : all;

// 	// console.log(current, count, instance);

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

// 	changeEvents();
// }



// // Взять индексы страницы
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



// // Выбрать строку для модального окна
// function getRow(instance, id, cb) {
// 	$.ajax({
// 		url: 'engine/ajax/getRow.php',
// 		type: 'POST',
// 		data: {instance: instance, id: id},
// 		success: (data) => {
// 			try {
// 				data = JSON.parse(data);
// 			} catch(e) {}

// 			// if error
// 			if (data.err) {
// 				$.notify(data.err, 'error');
// 				return;
// 			}

// 			cb(data.data);
// 		}
// 	});
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


