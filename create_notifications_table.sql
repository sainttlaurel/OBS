-- Create notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('success','info','warning') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_name` (`user_name`),
  KEY `is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample notifications for existing users
INSERT INTO `notifications` (`user_name`, `title`, `message`, `type`, `is_read`) 
SELECT user_name, 'Welcome to OBI Banking!', 'Your account has been successfully created. Start exploring our features!', 'success', 0
FROM users
WHERE NOT EXISTS (SELECT 1 FROM notifications WHERE notifications.user_name = users.user_name);

-- Add more sample notifications
INSERT INTO `notifications` (`user_name`, `title`, `message`, `type`) 
SELECT user_name, 'New Feature Available', 'Check out our new transaction export feature in Settings!', 'info'
FROM users LIMIT 5;

INSERT INTO `notifications` (`user_name`, `title`, `message`, `type`) 
SELECT user_name, 'Security Reminder', 'Remember to change your password regularly for better security.', 'warning'
FROM users LIMIT 5;
