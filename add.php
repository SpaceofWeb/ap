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
			<div class="jumbotron" id="drop-area-div">
				<h4>Добавить диплом</h4><br>

				<form method="POST" id="formAddDiploma" enctype="multipart/form-data">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Студент</span>
						<select class="form-control" name="student_id" id="studentSelect"></select>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Тема</span>
						<input type="text" class="form-control" name="subject" placeholder="Тема" required>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Год сдачи</span>
						<input type="text" class="form-control" name="year" placeholder="Год" value="<?=date("Y"); ?>" required>
					</div><br>

					<div class="input-group">
						<input type="file" name="files" id="file" class="custom-file-input1">
						<!-- <span class="custom-file-control"></span> -->
					</div><br>

					<div class="right">
						<input type="submit" name="addDiploma" class="btn btn-success" value="Добавить">
					</div>
				</form>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron">
				<h4>Добавить студента</h4><br>

				<form method="POST" id="formAddStudent">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Фамилия</span>
						<input type="text" class="form-control" name="firstName" placeholder="Фамилия" required>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Имя</span>
						<input type="text" class="form-control" name="middleName" placeholder="Имя" required>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Отчество</span>
						<input type="text" class="form-control" name="lastName" placeholder="Отчество" required>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Группа</span>
						<select class="form-control" name="group_id" id="groupSelect"></select>
					</div><br>

					<div class="right">
						<input type="submit" name="addStudent" class="btn btn-success" value="Добавить">
					</div>
				</form>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron">
				<h4>Добавить группу</h4><br>

				<form method="POST" id="formAddGroup" name="form">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Группа</span>
						<input type="text" class="form-control" id="group" name="name" placeholder="Название группы" required>
					</div><br>

					<div class="right">
						<input type="submit" name="addStudent" class="btn btn-success" value="Добавить">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>

$(document).ready(() => {

	var app = new AP;

	var groupSelect = app.getNode('#groupSelect'),
			studentSelect = app.getNode('#studentSelect'),
			formAddGroup = app.getJQNode('#formAddGroup'),
			formAddStudent = app.getJQNode('#formAddStudent'),
			formAddDiploma = app.getJQNode('#formAddDiploma'),
			formAddDiplomaFile = app.getNode('#file');


	getGroups();
	getStudents();


	// Добавить группу в базу
	formAddGroup.on('submit', (e) => {
		e.preventDefault();

		let q = {migration: 'groups', formData: formAddGroup.serializeArray()};
		app.ajax('add', 'json', q, (d) => {
			if (d.status == 'ok') {
				$.notify('Успешно добавленно!', 'success');
				formAddGroup[0].reset();
				getGroups();
			} else if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
			}
		});
	});


	// Добавить студента в базу
	formAddStudent.on('submit', (e) => {
		e.preventDefault();

		let q = {migration: 'students', formData: formAddStudent.serializeArray()};
		app.ajax('add', 'json', q, (d) => {
			if (d.status == 'ok') {
				$.notify('Успешно добавленно!', 'success');
				formAddStudent[0].reset();
				getStudents();
			} else if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
			}
		});
	});


	// Загрузить дипломную и добавить в базу
	formAddDiploma.on('submit', (e) => {
		e.preventDefault();

		let formDataSet = new FormData(),
				formDataArr = formAddDiploma.serializeArray();

		for (let i = 0; i < formDataArr.length; i++) {
			formDataSet.append(formDataArr[i].name, formDataArr[i].value);
		}
		formDataSet.append('file', formAddDiplomaFile.files[0]);
		formDataSet.append('migration', 'diplomas');

		// var q = {migration: 'diplomas'};
		app.ajaxFile('setter', 'json', formDataSet, (d) => {
			if (d.status == 'ok') {
				$.notify('Успешно добавленно!', 'success');
				formAddDiploma[0].reset();
				getGroups();
			} else if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
			}
		});
	});



	// Выбрать список групп и записать их в селектор
	function getGroups() {
		let q = {
			migration: 'groups',
			cols: ['id', 'name'],
			order: [
				{
					col: 'name',
					sort: 'asc'
				}
			]
		};

		app.ajax('get', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Невозможно получить список групп: '+d.data, 'error');
				console.log(d);
				return;
			}

			let html = '<option value="0">-</option>';
			for (let i = 0; i < d.data.length; i++) {
				html += '<option value="'+d.data[i].id+'">'+d.data[i].name+'</option>'
			}
			groupSelect.innerHTML = html;
		}, false);
	}


	// Выбрать список студентов и записать их в селектор
	function getStudents() {
		let q = {
			migration: 'students',
			cols: ['id', 'CONCAT(firstName, \' \', middleName, \' \', lastName) AS name'],
			order: [
				{
					col: 'group_id',
					sort: 'asc'
				},{
					col: 'firstName',
					sort: 'asc'
				}
			]
		};

		app.ajax('get', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Невозможно получить список студентов: '+d.data, 'error');
				console.log(d);
				return;
			}

			let html = '<option value="0">-</option>';
			for (let i = 0; i < d.data.length; i++) {
				html += '<option value="'+d.data[i].id+'">'+d.data[i].name+' '+'</option>'
			}
			studentSelect.innerHTML = html;
		}, false);
	}


});

</script>

<?php

require_once 'access/static/footer.php';
