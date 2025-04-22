USE gym_management;

-- Insert admin user (password: admin123)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2a$10$X9/vQrWqnO3USO1KVUoSte1IZ6K1YySM5elI0BpKYXJM7jHBTY9NS');

-- Insert sample members
INSERT INTO members (name, age, gender, address, phone, email) VALUES
('John Doe', 28, 'male', '123 Main St, Anytown, USA', '555-123-4567', 'john@example.com'),
('Jane Smith', 32, 'female', '456 Oak St, Somewhere, USA', '555-987-6543', 'jane@example.com');

-- Insert sample trainers
INSERT INTO trainers (name, age, address, experience_years, specialty, phone, email) VALUES
('Mike Johnson', 35, '789 Pine St, Somewhere, USA', 10, 'Weight Training', '555-555-1234', 'mike@example.com'),
('Sarah Williams', 30, '321 Elm St, Anywhere, USA', 8, 'Cardio', '555-555-5678', 'sarah@example.com'),
('David Lee', 28, '654 Maple St, Nowhere, USA', 5, 'Yoga', '555-555-9012', 'david@example.com');

-- Insert sample packages
INSERT INTO packages (name, duration_weeks, price, description) VALUES
('Basic', 4, 49.99, 'Access to gym facilities during regular hours'),
('Standard', 12, 129.99, 'Access to gym facilities and group classes');

-- Insert sample subscriptions
INSERT INTO subscriptions (member_id, package_id, start_date, end_date) VALUES
(1, 1, '2023-01-01', '2023-01-29'),
(2, 2, '2023-01-15', '2023-04-09');

-- Insert sample attendance records
INSERT INTO attendance (user_id, role, date, time_in, time_out) VALUES
(1, 'member', '2023-01-01', '09:00:00', '10:30:00'),
(1, 'member', '2023-01-02', '08:00:00', '09:30:00'),
(2, 'member', '2023-01-01', '17:00:00', '18:30:00'),
(1, 'trainer', '2023-01-01', '08:30:00', '17:30:00'),
(2, 'trainer', '2023-01-01', '09:00:00', '18:00:00');

-- Insert sample equipment
INSERT INTO equipment (name, purchase_date, last_service_date, next_service_date, status, notes) VALUES
('Treadmill #1', '2022-01-15', '2022-12-15', '2023-06-15', 'operational', 'Regular maintenance performed'),
('Bench Press #1', '2022-03-10', '2022-11-10', '2023-05-10', 'operational', 'Weight capacity: 300kg'),
('Rowing Machine #1', '2022-05-20', '2022-11-20', '2023-05-20', 'maintenance', 'Scheduled for maintenance');