import express from 'express';
import pool from '../config/db.js';
import auth from '../middleware/auth.js';

const router = express.Router();

// @route   GET /api/packages
// @desc    Get all packages
// @access  Private
router.get('/', auth, async (req, res) => {
  try {
    const [packages] = await pool.query('SELECT * FROM packages ORDER BY id DESC');
    res.json(packages);
  } catch (error) {
    console.error('Error fetching packages:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   GET /api/packages/:id
// @desc    Get a single package
// @access  Private
router.get('/:id', auth, async (req, res) => {
  const { id } = req.params;
  
  try {
    const [packages] = await pool.query('SELECT * FROM packages WHERE id = ?', [id]);
    
    if (packages.length === 0) {
      return res.status(404).json({ message: 'Package not found' });
    }
    
    res.json(packages[0]);
  } catch (error) {
    console.error('Error fetching package:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   POST /api/packages
// @desc    Create a new package
// @access  Private
router.post('/', auth, async (req, res) => {
  const { name, duration_weeks, price, description } = req.body;
  
  try {
    const [result] = await pool.query(
      'INSERT INTO packages (name, duration_weeks, price, description) VALUES (?, ?, ?, ?)',
      [name, duration_weeks, price, description]
    );
    
    res.status(201).json({ 
      id: result.insertId,
      message: 'Package added successfully' 
    });
  } catch (error) {
    console.error('Error adding package:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   PUT /api/packages/:id
// @desc    Update a package
// @access  Private
router.put('/:id', auth, async (req, res) => {
  const { id } = req.params;
  const { name, duration_weeks, price, description } = req.body;
  
  try {
    await pool.query(
      'UPDATE packages SET name = ?, duration_weeks = ?, price = ?, description = ? WHERE id = ?',
      [name, duration_weeks, price, description, id]
    );
    
    res.json({ message: 'Package updated successfully' });
  } catch (error) {
    console.error('Error updating package:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   DELETE /api/packages/:id
// @desc    Delete a package
// @access  Private
router.delete('/:id', auth, async (req, res) => {
  const { id } = req.params;
  
  try {
    // Check if package is in use
    const [subscriptions] = await pool.query('SELECT * FROM subscriptions WHERE package_id = ?', [id]);
    
    if (subscriptions.length > 0) {
      return res.status(400).json({ 
        message: 'Cannot delete package as it is currently used by members' 
      });
    }
    
    await pool.query('DELETE FROM packages WHERE id = ?', [id]);
    
    res.json({ message: 'Package deleted successfully' });
  } catch (error) {
    console.error('Error deleting package:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

export default router;