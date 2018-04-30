// Депенденси
jsdiff = require('string-similarity');
mysql = require('mysql');
fs = require('fs');

// Коннект к базе
let conn = mysql.createConnection({
	host: 'localhost',
	user: 'root',
	password: 'root',
	database: 'ap'
});



// Инициализация логов
let d = new Date();
let logFile = __dirname+'/logs/'+d.getFullYear()+(d.getMonth()+1)+d.getDate()+'.log';


if (!fs.existsSync(__dirname+'/logs/')) {
	fs.mkdirSync(__dirname+'/logs/');
}

log('info', 'Start session');



// Запрос в базу
let q = 'SELECT P.id, D.text AS d, D2.text AS d2\
	FROM ap_percentage P\
	LEFT JOIN ap_diplomas D ON P.d1_id=D.id\
	LEFT JOIN ap_diplomas D2 ON P.d2_id=D2.id\
	WHERE P.percent IS NULL\
	ORDER BY P.id LIMIT 1';


// Подключение к базе
conn.connect((err) => {
	if (err) {
		log('err', 'Can`t connect to db: '+err);
		return;
	}

	getQuery();
});




// Логирование
function log(t='info', msg, cb) {
	let d = new Date();

	let h = d.getHours(),
			m = d.getMinutes(),
			s = d.getSeconds();

	h = (0 <= h && h < 10) ? '0'+h : h;
	m = (0 <= m && m < 10) ? '0'+m : m;
	s = (0 <= s && s < 10) ? '0'+s : s;

	let time = h+':'+m+':'+s;

	t = t.toUpperCase();

	console.log('['+t+'] '+msg);
	fs.appendFile(logFile, time+' ['+t+'] '+msg+"\n", (err) => {
		if (err) {
			console.log('e:', err);
			throw err;
		}

		if (cb) cb();
	});
}


// Выборка записи
// Сравнение
function getQuery() {
	conn.query(q, function (err, res) {
		if (err) {
			log('err', 'Can`t execute query: '+err)
		}

		if (res.length > 0) {
			let t = new Date().getTime();
			let p = jsdiff.compareTwoStrings(res[0].d, res[0].d2);
			let time = new Date().getTime()-t;

			p = Math.round(p*100);
			setQuery(res[0].id, p);

			log('info', 'The comparsion is completed for id: '+res[0].id);
			log('info', 'Percents: '+p+'; Time: '+time+'ms');
			getQuery();

		} else {

			log('info', 'End session', () => {
				process.exit();
			});
		}
	});
}


// Запись результата в базу
function setQuery(id, p) {
	let setq = "UPDATE ap_percentage SET percent='"+p+"' WHERE id='"+id+"' ";
	conn.query(setq, function (err, res) {
		if (err) {
			log('err', 'Can`t set percent for id='+id+' : '+err)
		}
	});
}




