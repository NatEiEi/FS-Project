const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

const app = express();
const port = 3001;


// Enable CORS
app.use(cors());

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'shopdbnew'
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
  
  app.get('/api/data', (req, res) => {
    const { startDate, endDate } = req.query;
    connection.query(
        `SELECT pl.ProductID , p.ProductName , p.PricePerUnit , p.Cost , SUM(Qty) as total_Qty
        FROM productlist pl , product p  
        WHERE pl.ProductID = p.ProductID 
        AND OrderID IN 
            (SELECT OrderID FROM \`order\` WHERE Status != 'Canceled' AND Date BETWEEN ? AND ?) 
        GROUP BY ProductID 
        ORDER BY ProductID`,
        [startDate, endDate],
        (err, results) => {
            if (err) {
                console.error('Error fetching data from MySQL:', err);
                res.status(500).json({ error: 'Internal server error' });
                return;
            }
            // console.log('Data from MySQL Database:');
            // console.log(results);
  
            res.json(results);
        });
});


app.get('/api/cntOrder', (req, res) => {
  const { startDate, endDate } = req.query;
  connection.query(
      `SELECT * FROM \`order\` 
      WHERE Status != 'Canceled' AND Date BETWEEN ? AND ? ORDER BY Date`,
      [startDate, endDate],
      (err, results) => {
          if (err) {
              console.error('Error fetching data from MySQL:', err);
              res.status(500).json({ error: 'Internal server error' });
              return;
          }
          // console.log('Data from MySQL Database:');
          // console.log(results);

          res.json(results);
      });
});


app.get('/api/order', (req, res) => {
  const { startDate, endDate } = req.query;
  connection.query(
      `SELECT * FROM \`order\` 
      WHERE date BETWEEN ? AND ?`,
      [startDate, endDate],
      (err, results) => {
          if (err) {
              console.error('Error fetching data from MySQL:', err);
              res.status(500).json({ error: 'Internal server error' });
              return;
          }
          // console.log('Data from MySQL Database:');
          // console.log(results);

          res.json(results);
      });
});


app.get('/api/cancle', (req, res) => {
  const { startDate, endDate } = req.query;
  connection.query(
    `SELECT 
    pl.ProductID,
    p.ProductName,
    p.PricePerUnit,
    SUM(pl.Qty) AS TotalQty
    FROM 
        \`order\` o
    JOIN 
        productlist pl ON o.orderID = pl.orderID
    JOIN 
        product p ON pl.ProductID = p.ProductID
    WHERE 
        (o.Status IN (60, 70, 80) AND o.Date BETWEEN ? AND ?)
    GROUP BY 
    pl.ProductID, p.ProductName, p.PricePerUnit; `, 
    [startDate, endDate],
      (err, results) => {
          if (err) {
              console.error('Error fetching data from MySQL:', err);
              res.status(500).json({ error: 'Internal server error' });
              return;
          }
          // console.log('Data from MySQL Database:');
           console.log(results);

          res.json(results);
      });
});

app.get('/api/TopPD', (req, res) => {
  const { startDate, endDate } = req.query;
  connection.query(
    `SELECT pl.ProductID, SUM(pl.Qty) AS TotalQty, p.ProductName
    FROM productlist pl
    INNER JOIN Product p ON pl.ProductID = p.ProductID
    INNER JOIN \`order\` o ON pl.orderID = o.orderID
    WHERE o.Date BETWEEN ? AND ?
    GROUP BY pl.ProductID, p.ProductName
    ORDER BY TotalQty DESC;
    
    
    `, 
    [startDate, endDate],
      (err, results) => {
          if (err) {
              console.error('Error fetching data from MySQL:', err);
              res.status(500).json({ error: 'Internal server error' });
              return;
          }
          // console.log('Data from MySQL Database:');
           console.log(results);

          res.json(results);
      });
});

app.get('/api/forThai', (req, res) => {
  const { startDate, endDate } = req.query;
  connection.query(
      `
      SELECT DISTINCT o.username, a.zip, a.District
      FROM \`order\` o
      JOIN address a ON o.Username = a.Username
      JOIN addresslist al ON a.AddressID = al.AddressID
      WHERE o.Date BETWEEN ? AND ? AND al.Type = 'Buy';

      `,
      [startDate, endDate],
      (err, results) => {
          if (err) {
              console.error('Error fetching data from MySQL:', err);
              res.status(500).json({ error: 'Internal server error' });
              return;
          }
          // console.log('Data from MySQL Database:');
          // console.log(results);

          res.json(results);
      });
});
  
  


  const connection2 = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'thailand'
  });
  
  
  app.get('/api/thai', (req, res) => {
    const zip = req.query.zip;
    const district = req.query.district;
    connection2.query(
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
  
  connection2.connect((err) => {
      if (err) {
        console.error('Error connecting to MySQL database:', err);
        return;
      }
      console.log('Connected to MySQL database');
      
      // Test the connection
      connection2.ping((pingErr) => {
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
  
  
 