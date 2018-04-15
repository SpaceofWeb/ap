/**
* class for work with interface
*/
function AP() {
	self = this;
	self.lastQuery = 0;
	self.timeout = 3;
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


AP.prototype.ajax = function(type='get', datatype='json', d={}, cb, timeout=true) {
	if (timeout) {
		if (self.getUnixtime() - self.lastQuery < self.timeout) {
			cb({status: 'err', data: 'Wait for timeout'});
			return;
		}
		self.lastQuery = self.getUnixtime();
	}

	$.ajax({
		url: 'engine/ajax/'+type+'ter.php',
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


AP.prototype.ajaxFile = function(type='set', datatype='json', d={}, cb) {
	if (self.getUnixtime() - self.lastQuery < self.timeout) {
		cb({status: 'err', data: 'Wait for timeout'});
		return;
	}
	self.lastQuery = self.getUnixtime();

	$.ajax({
		url: 'engine/ajax/'+type+'ter.php',
		type: 'POST',
		dataType: datatype,
		processData: false,
		contentType: false,
		cache: false,
		data: d,
		xhr: function () {
			var myXhr = $.ajaxSettings.xhr();
			if (myXhr.upload) {
				myXhr.upload.addEventListener('progress', this.ajaxFileProccess, false);
			}
			return myXhr;
		},
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


AP.prototype.ajaxFileProccess = function(e) {
	var bar = self.getJQNode('.progress-bar'),
			percent = 0,
			position = e.loaded || e.position,
			total = e.total;

	if (e.lengthComputable) {
		percent = Math.ceil(position / total * 100);
	}

	bar.css("width", +percent + "%");
}