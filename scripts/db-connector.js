var mysql = require('mysql');

var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "nbgc"
});

con.connect(function (err) {
    if (err) throw err;
    con.query("SELECT * FROM calendar_events", function (err, result, fields) {
        if (err) throw err;
        console.log(result);
    });
});