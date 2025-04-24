import express from 'express';
import cors from 'cors';
import path from 'path';
import { fileURLToPath } from 'url';
import dotenv from 'dotenv';
import helmet from 'helmet';
import rateLimit from 'express-rate-limit';
import authRoutes from './routes/auth.js';
import memberRoutes from './routes/members.js';
// ... other route imports

// Routes imports...
dotenv.config();

const app = express();
const PORT = process.env.PORT || 3000;
const limiter = rateLimit({ windowMs: 15*60*1000, max: 100 });

// Middleware
app.use(cors());
app.use(helmet());
app.use(limiter);
app.use(express.json());

// DB Connection Check
import pool from './config/db.js';
pool.getConnection()
  .then(conn => {
    console.log('DB connected');
    conn.release();
  })
  .catch(err => {
    console.error('DB connection failed:', err);
    process.exit(1);
  });

// Static files
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
app.use(express.static(path.join(__dirname, '../../frontend')));

// Routes
app.use('/api', authRoutes);
// ... other routes

// Error handling
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ error: 'Internal Server Error' });
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});