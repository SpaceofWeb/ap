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
		<div class="col-md-6">
			<div class="jumbotron pb-md-1">
				<h4>Изменить студента</h4><br>

				<form id="sForm" class="form-inline mt-2 mt-md-0 was-validated">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="sSearch" class="form-control is-invalid" placeholder="ФИО">
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
							<span class="page-link pageInfo">1/1</span>
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

		<div class="col-md-6">
			<div class="jumbotron pb-md-1">
				<h4>Изменить группу</h4><br>

				<form id="gForm" class="form-inline mt-2 mt-md-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="gSearch" class="form-control" placeholder="Название группы">
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
							<span class="page-link pageInfo">1/1</span>
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

		<div class="col-md-12">
			<div class="jumbotron pb-md-1">
				<h4>Изменить диплом</h4><br>

				<form id="dForm" class="form-inline mt-2 mt-md-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="dSearch" class="form-control" placeholder="ФИО или Тема">
							</div>
						</div>
					</div>
				</form>

				<table class="table">
					<thead>
						<tr>
							<th>Студента</th>
							<th>Тема</th>
							<th class="right">Действия</th>
						</tr>
					</thead>
					<tbody id="tableDiplomas"></tbody>
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
							<span class="page-link pageInfo">1/1</span>
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
			<form method="POST" id="diplomasForm">
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Студент</span>
						<select class="form-control" name="student_id"></select>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Тема</span>
						<input type="text" class="form-control" name="subject" required>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Год защиты</span>
						<input type="text" class="form-control" name="year" required>
					</div><br>

					<div class="input-group">
						<input type="file" name="files" id="file" class="custom-file-input1">
						<!-- <span class="custom-file-control"></span> -->
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
			<form method="POST" id="studentsForm">
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
						<select class="form-control" name="group_id"></select>
					</div>
					<input type="hidden" name="id">
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
			<form method="POST" id="groupsForm">
				<div class="modal-body">
						<div class="input-group">
							<span class="input-group-addon bgcolor">Название группы</span>
							<input type="text" class="form-control" name="name">
							<input type="hidden" name="id">
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

	//////////////////////////////
	// Инициальзация переменных //
	//////////////////////////////
	var app = new AP;

	var tableGroups = app.getNode('#tableGroups'),
			tableStudents = app.getNode('#tableStudents'),
			tableDiplomas = app.getNode('#tableDiplomas'),
			gSearch = app.getJQNode('#gSearch'),
			sSearch = app.getJQNode('#sSearch'),
			dSearch = app.getJQNode('#dSearch'),
			groupsForm = app.getJQNode('#groupsForm'),
			studentsForm = app.getJQNode('#studentsForm'),
			diplomasForm = app.getJQNode('#diplomasForm'),
			diplomasFormFile = app.getNode('#diplomasForm #file'),
			pag = {
				groupsPag: {},
				studentsPag: {},
				diplomasPag: {}
			};

	pag.groupsPag.liFirst = app.getJQNode('#groupsPag li.first'),
	pag.groupsPag.aFirst = app.getJQNode('#groupsPag a.first'),
	pag.groupsPag.pageInfo = app.getNode('#groupsPag .pageInfo'),
	pag.groupsPag.liLast = app.getJQNode('#groupsPag li.last'),
	pag.groupsPag.aLast = app.getJQNode('#groupsPag a.last');

	pag.studentsPag.liFirst = app.getJQNode('#studentsPag li.first'),
	pag.studentsPag.aFirst = app.getJQNode('#studentsPag a.first'),
	pag.studentsPag.pageInfo = app.getNode('#studentsPag .pageInfo'),
	pag.studentsPag.liLast = app.getJQNode('#studentsPag li.last'),
	pag.studentsPag.aLast = app.getJQNode('#studentsPag a.last');

	pag.diplomasPag.liFirst = app.getJQNode('#diplomasPag li.first'),
	pag.diplomasPag.aFirst = app.getJQNode('#diplomasPag a.first'),
	pag.diplomasPag.pageInfo = app.getNode('#diplomasPag .pageInfo'),
	pag.diplomasPag.liLast = app.getJQNode('#diplomasPag li.last'),
	pag.diplomasPag.aLast = app.getJQNode('#diplomasPag a.last');
	// ==============================================================


	////////////////////////////////////////////
	// Выбрать записи после загрузки страницы //
	////////////////////////////////////////////
	getGroupList();
	getStudentList();
	getDiplomaList();
	// ==============================================================


	///////////
	// Поиск //
	///////////
	// группы
	gSearch.on('input', (e) => {
		e.preventDefault();

		getGroupList();
	});

	// студенты
	sSearch.on('input', (e) => {
		e.preventDefault();

		getStudentList(1);
	});

	// дипломные
	dSearch.on('input', (e) => {
		e.preventDefault();

		getDiplomaList(1);
	});


	///////////////
	// Пагинация //
	///////////////
	// группы . назад
	pag['groupsPag'].aFirst.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;
		getGroupList(p);
	});
	// группы . вперед
	pag['groupsPag'].aLast.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;
		getGroupList(p);
	});


	// студенты . назад
	pag['studentsPag'].aFirst.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;
		getStudentList(p);
	});
	// студенты . вперед
	pag['studentsPag'].aLast.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;
		getStudentList(p);
	});


	// дипломные . назад
	pag['diplomasPag'].aFirst.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;
		getDiplomaList(p);
	});
	// дипломные . вперед
	pag['diplomasPag'].aLast.on('click', (e) => {
		e.preventDefault();

		let p = e.currentTarget.dataset.page;
		getDiplomaList(p);
	});
	// ==============================================================


	///////////////
	// Изменение //
	///////////////
	// группы
	groupsForm.on('submit', (e) => {
		e.preventDefault();

		let q = {migration: 'groups'},
				fd = groupsForm.serializeArray();

		for (let i of fd) {
			q[i.name] = i.value;
		}

		update(q, (d) => {
			if (d.status == 'ok') {
				$.notify(d.data, 'success');
				getGroupList();
			} else if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
			}
		});
	});

	// студента
	studentsForm.on('submit', (e) => {
		e.preventDefault();

		let q = {migration: 'students'},
				fd = studentsForm.serializeArray();

		for (let i of fd) {
			q[i.name] = i.value;
		}

		update(q, (d) => {
			if (d.status == 'ok') {
				$.notify(d.data, 'success');
				getStudentList();
			} else if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
			}
		});
	});

	// дипломной
	diplomasForm.on('submit', (e) => {
		e.preventDefault();

		let formDataSet = new FormData(),
				formDataArr = diplomasForm.serializeArray();

		for (let i = 0; i < formDataArr.length; i++) {
			formDataSet.append(formDataArr[i].name, formDataArr[i].value);
		}

		formDataSet.append('file', diplomasFormFile.files[0]);
		formDataSet.append('migration', 'diplomas');

		updateFile(formDataSet, (d) => {
			if (d.status == 'ok') {
				$.notify(d.data, 'success');
				getDiplomaList();
				diplomasFormFile.value = '';
			} else if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
			}
		});
	});
	// ==============================================================


	/////////////////////////////
	// Открытие модальных окон //
	/////////////////////////////
	initChangeEvents();
	function initChangeEvents(inst) {
		if (inst == 'groups') {

			$('.btnGroupChange').on('click', (e) => {
				let q = {
					migration: 'groups',
					search: [{
						key: 'id',
						val: e.target.dataset.id,
						cond: '='
					}]
				};

				get(q, (d) => {
					setModal(q.migration, d.data[0]);
				});
			});

		} else if (inst == 'students') {

			$('.btnStudentChange').on('click', (e) => {
				let q = {
					migration: 'students',
					search: [{
						key: 'id',
						val: e.target.dataset.id,
						cond: '='
					}]
				};

				get(q, (d) => {
					setModal(q.migration, d.data[0]);
				});
			});

		} else if (inst == 'diplomas') {

			$('.btnDiplomaChange').on('click', (e) => {
				let q = {
					migration: 'diplomas',
					search: [{
						key: 'id',
						val: e.target.dataset.id,
						cond: '='
					}]
				};

				get(q, (d) => {
					setModal(q.migration, d.data[0]);
				});
			});

		}
	}
	// ==============================================================


	///////////////////////////////////////////
	// Подстановка значений в модальное окно //
	///////////////////////////////////////////
	function setModal(migration, d) {
		switch(migration) {
			case 'diplomas':
				$('#diplomas [name="id"]').val(d.id);
				$('#diplomas [name="year"]').val(d.year);
				$('#diplomas [name="subject"]').val(d.subject);
				genSelectorList(d.student_id, 'students', (list) => {
					$('#diplomas [name="student_id"]').html(list);
				});
			break;
			case 'students':
				$('#students [name="id"]').val(d.id);
				$('#students [name="firstName"]').val(d.firstName);
				$('#students [name="middleName"]').val(d.middleName);
				$('#students [name="lastName"]').val(d.lastName);
				genSelectorList(d.group_id, 'groups', (list) => {
					$('#students [name="group_id"]').html(list);
				});
			break;
			case 'groups':
				$('#groups [name="id"]').val(d.id);
				$('#groups [name="name"]').val(d.name);
			break;
		}
	}
	// ==============================================================


	///////////////////////
	// Установка списков //
	///////////////////////
	function setList(migration, d) {
		let rows = '';

		switch(migration) {
			case 'diplomas':
				for (var i = 0; i < d.length; i++) {
					rows += '<tr>\
										<td>'+d[i].student+'</td>\
										<td>'+d[i].subject+'</td>\
										<td class="right">\
											<button type="button" class="btn btn-warning btn-sm btnDiplomaChange" data-toggle="modal"\
													data-target="#diplomas" data-id="'+d[i].id+'">♻</button>\
										</td>\
									</tr>'; 
				}
				tableDiplomas.innerHTML = rows;
			break;
			case 'students':
				for (var i = 0; i < d.length; i++) {
					rows += '<tr>\
										<td>'+d[i].name+'</td>\
										<td class="right">\
											<button type="button" class="btn btn-warning btn-sm btnStudentChange" data-toggle="modal"\
													data-target="#students" data-id="'+d[i].id+'">♻</button>\
										</td>\
									</tr>'; 
				}
				tableStudents.innerHTML = rows;
			break;
			case 'groups':
				for (var i = 0; i < d.length; i++) {
					rows += '<tr>\
										<td>'+d[i].name+'</td>\
										<td class="right">\
											<button type="button" class="btn btn-warning btn-sm btnGroupChange" data-toggle="modal"\
													data-target="#groups" data-id="'+d[i].id+'">♻</button>\
										</td>\
									</tr>'; 
				}
				tableGroups.innerHTML = rows;
			break;
		}

		initChangeEvents(migration);
	}
	// ==============================================================


	/////////////////////////
	// Установка пагинации //
	/////////////////////////
	function setPagin(migration, page, count) {
		if (page <= 1) {
			pag[migration+'Pag'].liFirst.addClass('disabled');
			pag[migration+'Pag'].aFirst.attr('data-page', 1);
		} else {
			pag[migration+'Pag'].liFirst.removeClass('disabled');
			pag[migration+'Pag'].aFirst.attr('data-page', parseInt(page)-1);
		}

		pag[migration+'Pag'].pageInfo.innerText = page+'/'+count;

		if (page >= count) {
			pag[migration+'Pag'].liLast.addClass('disabled');
			pag[migration+'Pag'].aLast.attr('data-page', count);
		} else {
			pag[migration+'Pag'].liLast.removeClass('disabled');
			pag[migration+'Pag'].aLast.attr('data-page', parseInt(page)+1);
		}
	}
	// ==============================================================


	////////////////////
	// Запросы в базу //
	////////////////////
	// Выбрать записи
	function get(q={}, cb) {
		app.ajax('get', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
				return;
			}

			cb(d);
		}, false);
	}

	// Изменить записи
	function update(q={}, cb) {
		app.ajax('update', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
				return;
			}

			cb(d);
		});
	}

	// Изменить дипломные
	function updateFile(q={}, cb) {
		app.ajaxFile('update', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
				return;
			}

			cb(d);
		});
	}
	// ==============================================================


	/////////////////////////////////////////
	// Создание запроса для выбора списков //
	/////////////////////////////////////////
	// группы
	function getGroupList(page=1) {
		let where = [];
		if (gSearch.val() != '') {
			where = [{key: 'name', val: '%'+gSearch.val()+'%', cond: 'LIKE'}];
		}

		let q = {
			migration: 'groups',
			search: where,
			order: [
				{
					col: 'name',
					sort: 'asc'
				}
			],
			limit: page
		};

		get(q, (d) => {
			setList(q.migration, d.data);
			setPagin(q.migration, page, d.info.count);
		});
	}

	// студенты
	function getStudentList(page=1) {
		let where = [];
		if (sSearch.val() != '') {
			where = [{
				key: 'CONCAT(firstName, \' \', middleName, \' \', lastName)',
				val: '%'+sSearch.val()+'%',
				cond: 'LIKE'
			}];
		}

		let q = {
			migration: 'students',
			cols: ['id', 'CONCAT(firstName, \' \', middleName, \' \', lastName) AS name'],
			search: where,
			order: [
				{
					col: 'firstName',
					sort: 'asc'
				},{
					col: 'middleName',
					sort: 'asc'
				},{
					col: 'lastName',
					sort: 'asc'
				}
			],
			limit: page
		};

		get(q, (d) => {
			setList(q.migration, d.data);
			setPagin(q.migration, page, d.info.count);
		});
	}

	// дипломные
	function getDiplomaList(page=1) {
		let where = [];
		if (dSearch.val() != '') {
			where = [
				{
					key: 'd.subject',
					val: '%'+dSearch.val()+'%',
					cond: 'LIKE'
				},{
					key: 'CONCAT(s.firstName, \' \', s.middleName, \' \', s.lastName)',
					val: '%'+dSearch.val()+'%',
					cond: 'LIKE',
					or: true
				}
			];
		}

		let q = {
			migration: 'diplomas d',
			cols: [
				'd.id',
				'd.subject',
				'CONCAT(s.firstName, \' \', s.middleName, \' \', s.lastName) AS student'
			],
			search: where,
			join: [
				{
					type: 'LEFT',
					table: 'students s',
					cond: 's.id=d.student_id'
				}
			],
			order: [
				{
					col: 'firstName',
					sort: 'asc'
				},{
					col: 'middleName',
					sort: 'asc'
				},{
					col: 'lastName',
					sort: 'asc'
				}
			],
			limit:page
		};

		get(q, (d) => {
			setList('diplomas', d.data);
			setPagin('diplomas', page, d.info.count);
		});
	}
	// ==============================================================


	//////////////////////////////////
	// Генерация выпадающих списков //
	//////////////////////////////////
	function genSelectorList(id, migration, cb) {
		if (migration == 'groups') {

			let q = {
				migration: migration,
				cols: ['id', 'name'],
				order: [
					{
						col: 'name',
						sort: 'asc'
					}
				]
			};

			get(q, (d) => {
				let list = '';
				for (var i = 0; i < d.data.length; i++) {
					let sel = (id == d.data[i].id) ? ' selected' : '';
					list += '<option value="'+d.data[i].id+'" '+sel+'>'+d.data[i].name+'</option>';
				}
				cb(list);
			});

		} else if (migration == 'students') {

			let q = {
				migration: migration,
				cols: ['id', 'CONCAT(firstName, \' \', middleName, \' \', lastName) AS name'],
				order: [
					{
						col: 'firstName',
						sort: 'asc'
					},{
						col: 'middleName',
						sort: 'asc'
					},{
						col: 'lastName',
						sort: 'asc'
					}
				]
			};

			get(q, (d) => {
				let list = '';
				for (var i = 0; i < d.data.length; i++) {
					let sel = (id == d.data[i].id) ? ' selected' : '';
					list += '<option value="'+d.data[i].id+'" '+sel+'>'+d.data[i].name+'</option>';
				}
				cb(list);
			});

		}
	}
	// ==============================================================


});

</script>

<?php

require_once 'access/static/footer.php';


