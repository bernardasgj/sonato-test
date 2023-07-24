CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
);

CREATE TABLE pokes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    poked_by_user_id INT NOT NULL,
    poked_user_id INT NOT NULL,
    poked_at DATE NOT NULL,
    FOREIGN KEY (`poked_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`poked_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);