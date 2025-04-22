import express from 'express';
import pool from '../config/db.js';
import auth from '../middleware/auth.js';

const router = express.Router();

// @route   GET /api/equipment
// @desc    Get all equipment
// @access  Private
router.get('/', auth, async (req, res) => {
  try {
    const [equipment] = await pool.query('SELECT * FROM equipment ORDER BY id DESC');
    res.json(equipment);
  } catch (error) {
    console.error('Error fetching equipment:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   GET /api/equipment/:id
// @desc    Get a single equipment
// @access  Private
router.get('/:id', auth, async (req, res) => {
  const { id } = req.params;
  
  try {
    const [equipment] = await pool.query('SELECT * FROM equipment WHERE id = ?', [id]);
    
    if (equipment.length === 0) {
      return res.status(404).json({ message: 'Equipment not found' });
    }
    
    res.json(equipment[0]);
  } catch (error) {
    console.error('Error fetching equipment:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   POST /api/equipment
// @desc    Create a new equipment
// @access  Private
router.post('/', auth, async (req, res) => {
  const { name, purchase_date, last_service_date, next_service_date, status, notes } = req.body;
  
  try {
    const [result] = await pool.query(
      'INSERT INTO equipment (name, purchase_date, last_service_date, next_service_date, status, notes) VALUES (?, ?, ?, ?, ?, ?)',
      [name, purchase_date, last_service_date, next_service_date, status, notes]
    );
    
    res.status(201).json({ 
      id: result.insertId,
      message: 'Equipment added successfully' 
    });
  } catch (error) {
    console.error('Error adding equipment:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   PUT /api/equipment/:id
// @desc    Update an equipment
// @access  Private
router.put('/:id', auth, async (req, res) => {
  const { id } = req.params;
  const { name, purchase_date, last_service_date, next_service_date, status, notes } = req.body;
  
  try {
    await pool.query(
      'UPDATE equipment SET name = ?, purchase_date = ?, last_service_date = ?, next_service_date = ?, status = ?, notes = ? WHERE id = ?',
      [name, purchase_date, last_service_date, next_service_date, status, notes, id]
    );
    
    res.json({ message: 'Equipment updated successfully' });
  } catch (error) {
    console.error('Error updating equipment:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   DELETE /api/equipment/:id
// @desc    Delete an equipment
// @access  Private
router.delete('/:id', auth, async (req, res) => {
  const { id } = req.params;
  
  try {
    await pool.query('DELETE FROM equipment WHERE id = ?', [id]);
    
    res.json({ message: 'Equipment deleted successfully' });
  } catch (error) {
    console.error('Error deleting equipment:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

export default router;