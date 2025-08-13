-- schema.sql (MySQL) - creates users, rooms, bookings, messages, notifications, content
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(255) NOT NULL,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  photo VARCHAR(255),
  role VARCHAR(20) DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(50) NOT NULL,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(18,2) NOT NULL,
  image VARCHAR(255),
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  room_id INT,
  check_in DATE,
  payment_method VARCHAR(50),
  proof_image VARCHAR(255),
  reference_number VARCHAR(100),
  status VARCHAR(20) DEFAULT 'Pending',
  access_pin VARCHAR(30),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  subject VARCHAR(255),
  body TEXT,
  admin_reply TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(255),
  body TEXT,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS content (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(30) NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  image VARCHAR(255),
  date DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed rooms (sample)
INSERT INTO rooms (room_number, name, price, image, description) VALUES
('1210','Standard',2000.00,'/public/images/prdct1.jpg','Cozy standard room.'),
('1211','Standard Plus',2500.00,'/public/images/prdct2.jpg','Better view, comfortable.'),
('1212','Premium',3500.00,'/public/images/prdct2.jpg','Spacious premium room.'),
('1213','Deluxe',5693.00,'/public/images/prdct1.jpg','Deluxe with more amenities.'),
('1218','Gold Presidential Suite',999639976967123.00,'/public/images/prdct2.jpg','The ultimate presidential experience.');
