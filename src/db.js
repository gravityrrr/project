const mysql = require('mysql2');
require('dotenv').config();

// Create connection pool (better for web apps)
const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || 'Sand_disk06',
  database: process.env.DB_NAME || 'gym_management',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Test connection
pool.getConnection((err, conn) => {
  if (err) {
    console.error('Database connection failed:', err.stack);
    return;
  }
  console.log('Successfully connected to MySQL database!');
  conn.release();
});

module.exports = pool.promise(); // Enable async/await