import express from 'express';
import pool from '../config/db.js';
import auth from '../middleware/auth.js';

const router = express.Router();

// @route   GET /api/stats
// @desc    Get dashboard statistics
// @access  Private
router.get('/', auth, async (req, res) => {
  try {
    // Get counts for each entity
    const [memberCount] = await pool.query('SELECT COUNT(*) as count FROM members');
    const [trainerCount] = await pool.query('SELECT COUNT(*) as count FROM trainers');
    const [equipmentCount] = await pool.query('SELECT COUNT(*) as count FROM equipment');
    const [packageCount] = await pool.query('SELECT COUNT(*) as count FROM packages');
    
    // Return all stats
    res.json({
      members: memberCount[0].count,
      trainers: trainerCount[0].count,
      equipment: equipmentCount[0].count,
      packages: packageCount[0].count
    });
  } catch (error) {
    console.error('Error fetching stats:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

// @route   GET /api/stats/total-distribution
// @desc    Get distribution of members, trainers, equipment, packages
// @access  Private
router.get('/total-distribution', auth, async (req, res) => {
  try {
    // Get counts for each entity
    const [memberCount] = await pool.query('SELECT COUNT(*) as count FROM members');
    const [trainerCount] = await pool.query('SELECT COUNT(*) as count FROM trainers');
    const [equipmentCount] = await pool.query('SELECT COUNT(*) as count FROM equipment');
    const [packageCount] = await pool.query('SELECT COUNT(*) as count FROM packages');
    
    // Format the data for a chart
    res.json([
      { label: 'Members', count: memberCount[0].count },
      { label: 'Trainers', count: trainerCount[0].count },
      { label: 'Equipment', count: equipmentCount[0].count },
      { label: 'Packages', count: packageCount[0].count }
    ]);
  } catch (error) {
    console.error('Error fetching distribution stats:', error.message);
    res.status(500).json({ message: 'Server error' });
  }
});

export default router;