const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

const app = express();
const port = 3002;


// Enable CORS
app.use(cors());

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'thailand'
});


app.get('/api/thai', (req, res) => {
  const zip = req.query.zip;
  const district = req.query.district;
  connection.query(
      `
      SELECT 
      d.name_th AS district_name,
      a.name_th AS amphure_name,
      p.name_th AS province_name,
      g.name AS geography_name
      FROM 
          districts d
      JOIN 
          amphures a ON d.amphure_id = a.id
      JOIN 
          provinces p ON a.province_id = p.id
      JOIN 
          geographies g ON p.geography_id = g.id
      WHERE
          d.zip_code = ? and d.name_th = ?
      `,
      
      [zip,district],
      (err, results) => {
          if (err) {
              console.error('Error fetching data from MySQL:', err);
              res.status(500).json({ error: 'Internal server error' });
              return;
          }
        
          console.log(district);
          console.log(zip);
          console.log(results);
          res.json(results);
      });
});

connection.connect((err) => {
    if (err) {
      console.error('Error connecting to MySQL database:', err);
      return;
    }
    console.log('Connected to MySQL database');
    
    // Test the connection
    connection.ping((pingErr) => {
      if (pingErr) {
        console.error('Error pinging MySQL database:', pingErr);
      } else {
        console.log('MySQL database ping successful');
      }
    });
  });
  



  app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
  });