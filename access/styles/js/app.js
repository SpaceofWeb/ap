/**
* Класс для отправки запросов на сервер
*/
function AP() {
	self = this;
	self.lastQuery = 0;
	self.timeout = 1;
}



AP.prototype.getNode = function(node) {
	return document.querySelector(node);
}


AP.prototype.getJQNode = function(node) {
	return $(node);
}


AP.prototype.getUnixtime = function() {
	return Math.round(new Date().getTime() / 1000);
}

// Запрос
AP.prototype.ajax = function(script, datatype='json', d={}, cb, timeout=true) {
	if (timeout) {
		if (self.getUnixtime() - self.lastQuery < self.timeout) {
			cb({status: 'err', data: 'Wait for timeout'});
			return;
		}
		self.lastQuery = self.getUnixtime();
	}

	$.ajax({
		url: 'engine/ajax/'+script+'.php',
		type: 'POST',
		dataType: datatype,
		data: d,
		success: (data) => {
			if (data.status == 'err') {
				console.error('Server response error:', data.data);
			}
			cb(data);
		},
		error: (data) => {
			console.error('Ajax error:', data);
			cb({status: 'err', data: 'ajax error, check console'});
		}
	});
}

// Запрос с файлом
AP.prototype.ajaxFile = function(script, datatype='json', d={}, cb) {
	if (self.getUnixtime() - self.lastQuery < self.timeout) {
		cb({status: 'err', data: 'Wait for timeout'});
		return;
	}
	self.lastQuery = self.getUnixtime();

	$.ajax({
		url: 'engine/ajax/'+script+'.php',
		type: 'POST',
		dataType: datatype,
		processData: false,
		contentType: false,
		cache: false,
		data: d,
		success: (data) => {
			if (data.status == 'err') {
				console.error('Server response error:', data.data);
			}
			cb(data);
		},
		error: (data) => {
			console.error('Ajax error:', data);
			cb({status: 'err', data: 'ajax error, check console'});
		}
	});
}

