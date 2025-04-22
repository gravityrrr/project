import express from 'express';
import pool from '../config/db.js';
import auth from '../middleware/auth.js';

const router = express.Router();

// @route   GET /api/trainers
// @desc    Get all trainers
// @access  Private
router.get('/', auth, async (req, res) => {
  try {
    const [trainers] = await pool.query('SELECT * FROM trainers ORDER BY id DESC');
    res.json(trainers);
  } catch (error) {
    console.error('Error fetching trainers:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   GET /api/trainers/:id
// @desc    Get a single trainer
// @access  Private
router.get('/:id', auth, async (req, res) => {
  const { id } = req.params;
  
  try {
    const [trainers] = await pool.query('SELECT * FROM trainers WHERE id = ?', [id]);
    
    if (trainers.length === 0) {
      return res.status(404).json({ message: 'Trainer not found' });
    }
    
    res.json(trainers[0]);
  } catch (error) {
    console.error('Error fetching trainer:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   POST /api/trainers
// @desc    Create a new trainer
// @access  Private
router.post('/', auth, async (req, res) => {
  const { name, age, address, experience_years, specialty, phone, email } = req.body;
  
  try {
    const [result] = await pool.query(
      'INSERT INTO trainers (name, age, address, experience_years, specialty, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)',
      [name, age, address, experience_years, specialty, phone, email]
    );
    
    res.status(201).json({ 
      id: result.insertId,
      message: 'Trainer added successfully' 
    });
  } catch (error) {
    console.error('Error adding trainer:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   PUT /api/trainers/:id
// @desc    Update a trainer
// @access  Private
router.put('/:id', auth, async (req, res) => {
  const { id } = req.params;
  const { name, age, address, experience_years, specialty, phone, email } = req.body;
  
  try {
    await pool.query(
      'UPDATE trainers SET name = ?, age = ?, address = ?, experience_years = ?, specialty = ?, phone = ?, email = ? WHERE id = ?',
      [name, age, address, experience_years, specialty, phone, email, id]
    );
    
    res.json({ message: 'Trainer updated successfully' });
  } catch (error) {
    console.error('Error updating trainer:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   DELETE /api/trainers/:id
// @desc    Delete a trainer
// @access  Private
router.delete('/:id', auth, async (req, res) => {
  const { id } = req.params;
  
  try {
    const connection = await pool.getConnection();
    
    try {
      await connection.beginTransaction();
      
      // Delete attendance records
      await connection.query('DELETE FROM attendance WHERE user_id = ? AND role = "trainer"', [id]);
      
      // Delete trainer
      await connection.query('DELETE FROM trainers WHERE id = ?', [id]);
      
      await connection.commit();
      
      res.json({ message: 'Trainer deleted successfully' });
    } catch (error) {
      await connection.rollback();
      throw error;
    } finally {
      connection.release();
    }
  } catch (error) {
    console.error('Error deleting trainer:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

export default router;