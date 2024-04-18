const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

const app = express();
const port = 5000;


// Enable CORS
app.use(cors());

const connection = mysql.createConnection({
  host: 'mysqldb',
  user: 'root',
  password: 'example',
  database: 'shopdb'
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
    connection.query(
        `SELECT * FROM product`,
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

  
//   app.get('/api/data', (req, res) => {
//     const { startDate, endDate } = req.query;
//     connection.query(
//         `SELECT pl.ProductID , p.ProductName , p.PricePerUnit , p.Cost , SUM(Qty) as total_Qty
//         FROM productlist pl , product p  
//         WHERE pl.ProductID = p.ProductID 
//         AND OrderID IN 
//             (SELECT OrderID FROM \`order\` WHERE Status != '60' AND Status != '80' AND Date BETWEEN ? AND ?) 
//         GROUP BY ProductID 
//         ORDER BY ProductID`,
//         [startDate, endDate],
//         (err, results) => {
//             if (err) {
//                 console.error('Error fetching data from MySQL:', err);
//                 res.status(500).json({ error: 'Internal server error' });
//                 return;
//             }
//             // console.log('Data from MySQL Database:');
//             // console.log(results);
  
//             res.json(results);
//         });
// });


app.get('/api/cntOrder', (req, res) => {
  const { startDate, endDate } = req.query;
  connection.query(
      `SELECT * FROM \`order\` 
      WHERE Status != '60' AND Status != '80' AND Date BETWEEN ? AND ? ORDER BY Date`,
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
        (o.Status IN (60 , 80) AND o.Date BETWEEN ? AND ?)
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

    
    app.listen(port, () => {
      console.log(`Server is running on port ${port}`);
    });
  
  
 