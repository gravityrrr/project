import express from 'express';
import pool from '../config/db.js';
import auth from '../middleware/auth.js';

const router = express.Router();

// @route   GET /api/members
// @desc    Get all members
// @access  Private
router.get('/', auth, async (req, res) => {
  try {
    const [members] = await pool.query(`
      SELECT m.*, s.package_id, p.name as package_name, s.start_date, s.end_date 
      FROM members m
      LEFT JOIN subscriptions s ON m.id = s.member_id
      LEFT JOIN packages p ON s.package_id = p.id
      ORDER BY m.id DESC
    `);
    
    res.json(members);
  } catch (error) {
    console.error('Error fetching members:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   POST /api/members
// @desc    Create a new member
// @access  Private
router.post('/', auth, async (req, res) => {
  const { name, age, gender, address, phone, email, package_id, start_date } = req.body;
  
  try {
    const connection = await pool.getConnection();
    
    try {
      await connection.beginTransaction();
      
      // Insert member
      const [result] = await connection.query(
        'INSERT INTO members (name, age, gender, address, phone, email) VALUES (?, ?, ?, ?, ?, ?)',
        [name, age, gender, address, phone, email]
      );
      
      const memberId = result.insertId;
      
      // If package is selected, create subscription
      if (package_id) {
        // Get package duration to calculate end_date
        const [packages] = await connection.query('SELECT duration_weeks FROM packages WHERE id = ?', [package_id]);
        
        if (packages.length > 0) {
          const durationWeeks = packages[0].duration_weeks;
          
          // Calculate end date (start_date + duration_weeks in weeks)
          const endDate = new Date(start_date);
          endDate.setDate(endDate.getDate() + (durationWeeks * 7));
          
          // Insert subscription
          await connection.query(
            'INSERT INTO subscriptions (member_id, package_id, start_date, end_date) VALUES (?, ?, ?, ?)',
            [memberId, package_id, start_date, endDate.toISOString().split('T')[0]]
          );
        }
      }
      
      await connection.commit();
      
      res.status(201).json({ 
        id: memberId,
        message: 'Member added successfully' 
      });
    } catch (error) {
      await connection.rollback();
      throw error;
    } finally {
      connection.release();
    }
  } catch (error) {
    console.error('Error adding member:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   PUT /api/members/:id
// @desc    Update a member
// @access  Private
router.put('/:id', auth, async (req, res) => {
  const { id } = req.params;
  const { name, age, gender, address, phone, email, package_id, start_date } = req.body;
  
  try {
    const connection = await pool.getConnection();
    
    try {
      await connection.beginTransaction();
      
      // Update member
      await connection.query(
        'UPDATE members SET name = ?, age = ?, gender = ?, address = ?, phone = ?, email = ? WHERE id = ?',
        [name, age, gender, address, phone, email, id]
      );
      
      // Update subscription if package_id is provided
      if (package_id) {
        // Check if subscription exists
        const [subscriptions] = await connection.query(
          'SELECT * FROM subscriptions WHERE member_id = ?',
          [id]
        );
        
        // Get package duration
        const [packages] = await connection.query('SELECT duration_weeks FROM packages WHERE id = ?', [package_id]);
        
        if (packages.length > 0) {
          const durationWeeks = packages[0].duration_weeks;
          
          // Calculate end date
          const endDate = new Date(start_date);
          endDate.setDate(endDate.getDate() + (durationWeeks * 7));
          const formattedEndDate = endDate.toISOString().split('T')[0];
          
          if (subscriptions.length > 0) {
            // Update existing subscription
            await connection.query(
              'UPDATE subscriptions SET package_id = ?, start_date = ?, end_date = ? WHERE member_id = ?',
              [package_id, start_date, formattedEndDate, id]
            );
          } else {
            // Create new subscription
            await connection.query(
              'INSERT INTO subscriptions (member_id, package_id, start_date, end_date) VALUES (?, ?, ?, ?)',
              [id, package_id, start_date, formattedEndDate]
            );
          }
        }
      }
      
      await connection.commit();
      
      res.json({ message: 'Member updated successfully' });
    } catch (error) {
      await connection.rollback();
      throw error;
    } finally {
      connection.release();
    }
  } catch (error) {
    console.error('Error updating member:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   DELETE /api/members/:id
// @desc    Delete a member
// @access  Private
router.delete('/:id', auth, async (req, res) => {
  const { id } = req.params;
  
  try {
    const connection = await pool.getConnection();
    
    try {
      await connection.beginTransaction();
      
      // Delete subscriptions
      await connection.query('DELETE FROM subscriptions WHERE member_id = ?', [id]);
      
      // Delete attendance records
      await connection.query('DELETE FROM attendance WHERE user_id = ? AND role = "member"', [id]);
      
      // Delete member
      await connection.query('DELETE FROM members WHERE id = ?', [id]);
      
      await connection.commit();
      
      res.json({ message: 'Member deleted successfully' });
    } catch (error) {
      await connection.rollback();
      throw error;
    } finally {
      connection.release();
    }
  } catch (error) {
    console.error('Error deleting member:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

export default router;