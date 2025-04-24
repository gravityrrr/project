import express from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import pool from '../config/db.js';
import dotenv from 'dotenv';

dotenv.config();
const router = express.Router();

// @route   POST /api/login
// @desc    Authenticate admin & get token
// @access  Public
router.post('/login', async (req, res) => {
  const { username, password } = req.body;

  try {
    // 1. Check if admin exists
    const [admins] = await pool.query(
      "SELECT * FROM admins WHERE username = ?", 
      [username]
    );

    if (admins.length === 0) {
      return res.status(401).json({ error: "Invalid credentials" });
    }

    const admin = admins[0];

    // 2. Verify password
    const isMatch = await bcrypt.compare(password, admin.password);
    if (!isMatch) {
      return res.status(401).json({ error: "Invalid credentials" });
    }

    // 3. Generate JWT token
    const token = jwt.sign(
      { id: admin.id }, 
      process.env.JWT_SECRET || 'your_fallback_secret', 
      { expiresIn: '1h' }
    );

    res.json({ token });

  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ error: "Server error" });
  }
});

export default router;