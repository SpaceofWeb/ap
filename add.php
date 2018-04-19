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
		<div class="col-md-4">
			<div class="jumbotron" id="drop-area-div">
				<h4>Добавить диплом</h4><br>

				<form method="POST" id="formAddDiploma" enctype="multipart/form-data">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Студент</span>
						<select class="form-control" name="student_id" id="studentSelect"></select>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Год сдачи</span>
						<input type="text" class="form-control" name="year" id="year" value="<?=date("Y"); ?>" required>
					</div><br>

					<div class="input-group">
						<input type="file" name="files" id="file" class="custom-file-input1">
						<!-- <span class="custom-file-control"></span> -->
					</div><br>

					<div id="progress" class="progress">
						<div class="progress-bar progress-bar-striped" role="progressbar" style="width:0%"
								aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
						<input type="text" class="form-control" name="firstName" required>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Имя</span>
						<input type="text" class="form-control" name="middleName" required>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Отчество</span>
						<input type="text" class="form-control" name="lastName" required>
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
						<span class="input-group-addon bgcolor">Имя группы</span>
						<input type="text" class="form-control" id="group" name="name" required>
					</div><br>

					<div class="right">
						<input type="submit" name="addStudent" class="btn btn-success" value="Добавить">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- <script src="access/styles/js/jq-upload.js"></script> -->

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

		var q = {migration: 'groups', formData: formAddGroup.serializeArray()};
		app.ajax('set', 'json', q, (d) => {
			if (d.status == 'ok') {
				$.notify('Success added!', 'success');
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

		var q = {migration: 'students', formData: formAddStudent.serializeArray()};
		app.ajax('set', 'json', q, (d) => {
			if (d.status == 'ok') {
				$.notify('Success added!', 'success');
				formAddStudent[0].reset();
				getGroups();
			} else if (d.status == 'err') {
				$.notify('Error: '+d.data, 'error');
				console.log(d);
			}
		});
	});


	// Загрузить дипломную и добавить в базу
	formAddDiploma.on('submit', (e) => {
		e.preventDefault();

		var formDataSet = new FormData(),
				formDataArr = formAddDiploma.serializeArray();

		for (var i = 0; i < formDataArr.length; i++) {
			formDataSet.append(formDataArr[i].name, formDataArr[i].value);
		}
		formDataSet.append('file', formAddDiplomaFile.files[0]);
		formDataSet.append('migration', 'diplomas');

		// var q = {migration: 'diplomas'};
		app.ajaxFile('set', 'json', formDataSet, (d) => {
			if (d.status == 'ok') {
				$.notify('Success added!', 'success');
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
		var q = {migration: 'groups', order: 'name'};

		app.ajax('get', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Can`t get group list: '+d.data, 'error');
				console.log(d);
				return;
			}

			var html = '<option value="0">-</option>';

			for (var i = 0; i < d.data.length; i++) {
				html += '<option value="'+d.data[i].id+'">'+d.data[i].name+'</option>'
			}

			groupSelect.innerHTML = html;
		}, false);
	}


	// Выбрать список студентов и записать их в селектор
	function getStudents() {
		var q = {migration: 'students', order: ['group_id', 'firstName']};

		app.ajax('get', 'json', q, (d) => {
			if (d.status == 'err') {
				$.notify('Error: Can`t get student list: '+d.data, 'error');
				console.log(d);
				return;
			}

			var html = '<option value="0">-</option>';

			for (var i = 0; i < d.data.length; i++) {
				html += '<option value="'+d.data[i].id+'">'
							+d.data[i].firstName+' '+d.data[i].middleName+' '+d.data[i].lastName
							+' '+'</option>'
			}

			studentSelect.innerHTML = html;
		}, false);
	}










});





// $(document).ready(() => {


// var formAddDiploma = $('#formAddDiploma'),
// 	formAddStudent = $('#formAddStudent'),
// 	formAddGroup = $('#formAddGroup');



// // Выбрать список студентов, групп
// function getOptions(instance) {
// 	$.ajax({
// 		url: 'engine/ajax/getOptions.php',
// 		type: 'POST',
// 		data: {instance: instance},
// 		success: (data) => {
// 			try {
// 				data = JSON.parse(data);
// 			} catch(e) {}


// 			if (data.err) {
// 				$.notify(data.err, 'error');
// 			} else {
// 				if (instance == 'students') {
// 					$('#student').html(data.data);
// 				} else if (instance == 'groups') {
// 					$('#groupSelect').html(data.data);
// 				}
// 			}
// 		}
// 	});
// }



// // Добавить группу в базу
// formAddGroup.on('submit', (e) => {
// 	e.preventDefault();

// 	$.ajax({
// 		url: 'engine/ajax/addGroup.php',
// 		type: 'POST',
// 		data: formAddGroup.serialize(),
// 		success: (data) => {
// 			try {
// 				data = JSON.parse(data);
// 			} catch(e) {}


// 			if (data.err) {
// 				$.notify(data.err, 'error');
// 			} else {
// 				$.notify(data.success, 'success');
// 				formAddGroup[0].reset();
// 				getOptions('groups');
// 			}
// 		}
// 	});
// });



// // Добавить студента в базу
// formAddStudent.on('submit', (e) => {
// 	e.preventDefault();

// 	$.ajax({
// 		url: 'engine/ajax/addStudent.php',
// 		type: 'POST',
// 		data: formAddStudent.serialize(),
// 		success: (data) => {
// 			try {
// 				data = JSON.parse(data);
// 			} catch(e) {}


// 			if (data.err) {
// 				$.notify(data.err, 'error');
// 			} else {
// 				$.notify(data.success, 'success');
// 				formAddStudent[0].reset();
// 				getOptions('students');
// 			}
// 		}
// 	});
// });



// // Загрузить дипломную и добавить в базу
// formAddDiploma.jqUpload({
// 	url: 'engine/ajax/addDiplomas.php',
// 	// dataType: 'json',
// 	dataType: 'text',
// 	allowedTypes: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
// 	extFilter: 'docx',
// 	getData: () => {
// 		return {
// 			student: $('#student').val(),
// 			year: $('#year').val()
// 		}
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
// 			formAddDiploma[0].reset();

// 			setTimeout(() => {
// 				$('div.progress-bar').width('0%');
// 			}, 2000);
// 		}
// 	},
// 	onHaventFile: () => {
// 		$.notify('Вы не выбрали файл', 'warn');
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


// });

</script>

<?php

require_once 'access/static/footer.php';
