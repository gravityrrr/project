import express from 'express';
import pool from '../config/db.js';
import auth from '../middleware/auth.js';

const router = express.Router();

// @route   GET /api/attendance
// @desc    Get all attendance records with optional filters
// @access  Private
router.get('/', auth, async (req, res) => {
  try {
    const { startDate, endDate, role, userId } = req.query;
    
    let query = `
      SELECT a.*, 
        CASE 
          WHEN a.role = 'member' THEN m.name
          WHEN a.role = 'trainer' THEN t.name
        END as name
      FROM attendance a
      LEFT JOIN members m ON a.user_id = m.id AND a.role = 'member'
      LEFT JOIN trainers t ON a.user_id = t.id AND a.role = 'trainer'
      WHERE 1=1
    `;
    
    const queryParams = [];
    
    // Add filters if provided
    if (startDate) {
      query += ' AND a.date >= ?';
      queryParams.push(startDate);
    }
    
    if (endDate) {
      query += ' AND a.date <= ?';
      queryParams.push(endDate);
    }
    
    if (role) {
      query += ' AND a.role = ?';
      queryParams.push(role);
    }
    
    if (userId) {
      query += ' AND a.user_id = ?';
      queryParams.push(userId);
    }
    
    query += ' ORDER BY a.date DESC, a.time_in DESC';
    
    const [attendance] = await pool.query(query, queryParams);
    
    res.json(attendance);
  } catch (error) {
    console.error('Error fetching attendance:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   POST /api/attendance
// @desc    Record attendance (check-in)
// @access  Private
router.post('/', auth, async (req, res) => {
  const { user_id, role, date, time_in } = req.body;
  
  try {
    // Verify the user exists based on role
    let userExists = false;
    
    if (role === 'member') {
      const [members] = await pool.query('SELECT id FROM members WHERE id = ?', [user_id]);
      userExists = members.length > 0;
    } else if (role === 'trainer') {
      const [trainers] = await pool.query('SELECT id FROM trainers WHERE id = ?', [user_id]);
      userExists = trainers.length > 0;
    }
    
    if (!userExists) {
      return res.status(404).json({ message: `${role} not found` });
    }
    
    // Check if already checked in for the day
    const [existing] = await pool.query(
      'SELECT * FROM attendance WHERE user_id = ? AND role = ? AND date = ? AND time_out IS NULL',
      [user_id, role, date]
    );
    
    if (existing.length > 0) {
      return res.status(400).json({ message: 'Already checked in' });
    }
    
    const [result] = await pool.query(
      'INSERT INTO attendance (user_id, role, date, time_in) VALUES (?, ?, ?, ?)',
      [user_id, role, date, time_in]
    );
    
    res.status(201).json({ 
      id: result.insertId,
      message: 'Check-in recorded successfully' 
    });
  } catch (error) {
    console.error('Error recording attendance:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   PUT /api/attendance/:id
// @desc    Update attendance (check-out)
// @access  Private
router.put('/:id', auth, async (req, res) => {
  const { id } = req.params;
  const { time_out } = req.body;
  
  try {
    // Verify attendance record exists
    const [attendance] = await pool.query('SELECT * FROM attendance WHERE id = ?', [id]);
    
    if (attendance.length === 0) {
      return res.status(404).json({ message: 'Attendance record not found' });
    }
    
    // Check if already checked out
    if (attendance[0].time_out) {
      return res.status(400).json({ message: 'Already checked out' });
    }
    
    await pool.query(
      'UPDATE attendance SET time_out = ? WHERE id = ?',
      [time_out, id]
    );
    
    res.json({ message: 'Check-out recorded successfully' });
  } catch (error) {
    console.error('Error updating attendance:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   GET /api/attendance/last-seven-days
// @desc    Get attendance count for last 7 days
// @access  Private
router.get('/last-seven-days', auth, async (req, res) => {
  try {
    // Get the last 7 days
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 6); // 7 days including today
    
    // Format dates for query
    const formatDate = (date) => {
      return date.toISOString().split('T')[0];
    };
    
    // Get all dates in the range (including days with no attendance)
    const dates = [];
    for (let i = 0; i < 7; i++) {
      const date = new Date(startDate);
      date.setDate(date.getDate() + i);
      dates.push(formatDate(date));
    }
    
    // Query to get attendance counts by date and role
    const [results] = await pool.query(`
      SELECT date, role, COUNT(*) as count
      FROM attendance
      WHERE date BETWEEN ? AND ?
      GROUP BY date, role
      ORDER BY date
    `, [formatDate(startDate), formatDate(endDate)]);
    
    // Format the results
    const attendanceByDate = {};
    dates.forEach(date => {
      attendanceByDate[date] = { member: 0, trainer: 0 };
    });
    
    results.forEach(row => {
      attendanceByDate[row.date][row.role] = row.count;
    });
    
    // Convert to array format for easier use in frontend
    const formattedResult = dates.map(date => ({
      date,
      member: attendanceByDate[date].member,
      trainer: attendanceByDate[date].trainer
    }));
    
    res.json(formattedResult);
  } catch (error) {
    console.error('Error fetching attendance:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

export default router;