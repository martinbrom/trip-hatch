USE triphatch;

-- -----------------------------------------------------
-- ACTION TYPES
-- -----------------------------------------------------
INSERT INTO `action_types` (`id`, `name`, `icon_class`, `color_class`) VALUES
  (NULL, 'bus', 'fa-bus', 'transport'),
  (NULL, 'plane', 'fa-plane', 'transport'),
  (NULL, 'train', 'fa-train', 'transport'),
  (NULL, 'car', 'fa-car', 'transport'),
  (NULL, 'important', 'fa-star', 'important'),
  (NULL, 'point', 'fa-map-marker', 'interest'),
  (NULL, 'drink', 'fa-glass', 'food'),
  (NULL, 'food', 'fa-cutlery', 'food'),
  (NULL, 'coffee', 'fa-coffee', 'food'),
  (NULL, 'accommodation', 'fa-bed', 'accommodation'),
  (NULL, 'information', 'fa-info', 'other');

-- -----------------------------------------------------
-- IMAGES
-- -----------------------------------------------------
INSERT INTO `images` (`id`, `path`, `description`, `created_at`, `updated_at`) VALUES
  (1, 'avatar-default.jpg', 'default user image', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, 'mountains.jpg', 'default trip and action image', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- -----------------------------------------------------
-- USERS
-- -----------------------------------------------------
INSERT INTO `users` (`id`, `email`, `password`, `display_name`, `password_reset_token`, `is_admin`, `image_id`, `created_at`, `updated_at`) VALUES
  (NULL, 'testemail1@test.test', '$2a$12$yVrQshXzeDfCi87fuYVIMuK5e1otEBf5ByyQGPMI4r4Z7jJGzJ3Y.', 'Test User 1', NULL, '1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'testemail2@test.test', '$2a$12$yVrQshXzeDfCi87fuYVIMuK5e1otEBf5ByyQGPMI4r4Z7jJGzJ3Y.', NULL, NULL, '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'testemail3@test.test', '$2a$12$yVrQshXzeDfCi87fuYVIMuK5e1otEBf5ByyQGPMI4r4Z7jJGzJ3Y.', 'Test User 3', NULL, '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'testemail4@test.test', '$2a$12$yVrQshXzeDfCi87fuYVIMuK5e1otEBf5ByyQGPMI4r4Z7jJGzJ3Y.', NULL, NULL, '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'testemail5@test.test', '$2a$12$yVrQshXzeDfCi87fuYVIMuK5e1otEBf5ByyQGPMI4r4Z7jJGzJ3Y.', 'Test User 5', NULL, '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- -----------------------------------------------------
-- TRIPS
-- -----------------------------------------------------
INSERT INTO `trips` (`id`, `title`, `start_date`, `image_id`, `created_at`, `updated_at`) VALUES
  (NULL, 'Test trip 1', NULL, '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Test trip 2', NULL, '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Test trip 3', NULL, '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- -----------------------------------------------------
-- USER TRIP CROSS REFERENCE
-- -----------------------------------------------------
INSERT INTO `user_trip_xref` (`id`, `user_id`, `trip_id`, `role`, `created_at`, `updated_at`) VALUES
  (NULL, '1', '1', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '2', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '3', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '4', '1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '5', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '1', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '3', '2', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '5', '2', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '2', '3', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '3', '3', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '4', '3', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, '5', '3', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- -----------------------------------------------------
-- DAYS
-- -----------------------------------------------------
INSERT INTO `days` (`id`, `title`, `order`, `image_id`, `trip_id`, `created_at`, `updated_at`) VALUES
  (NULL, 'Day 1', '0', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Day 2', '1', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Day 3', '2', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Day 1', '0', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Day 2', '1', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Day 3', '2', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Day 4', '3', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Day 5', '4', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- -----------------------------------------------------
-- ACTIONS
-- -----------------------------------------------------
INSERT INTO `actions`(`id`, `title`, `content`, `order`, `day_id`, `action_type_id`, `created_at`, `updated_at`) VALUES
  (NULL, 'Arrival at Some Random Airport (SRA) - 9:35', 'Action content', '1', '1', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Train ride from Random Station 1 to Random Station 2 - 10:30 to 12:15', 'Action content', '2', '1', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Registering for Random National Park permits', 'Action content', '3', '1', '5', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Random interesting historic building', 'Action content', '4', '1', '6', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Lunch at a random cool place', 'Action content', '5', '1', '8', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Air-bnb in a cool beach house', 'Action content', '6', '1', '10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Action 1', 'Action content', '1', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Action 2', 'Action content', '2', '2', '6', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Action 3', 'Action content', '3', '2', '10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Action 1', 'Action content', '1', '3', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- -----------------------------------------------------
-- TRIP COMMENTS
-- -----------------------------------------------------
INSERT INTO `trip_comments`(`id`, `content`, `user_trip_id`, `created_at`, `updated_at`) VALUES
  (NULL, 'So what do you think guys?', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'I would rather eat burgers than italian food on day 2...', 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Yea, good idea!', 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Sure, I\'ll change the plan accordingly.', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'And what about visiting a ZOO on day 3?', 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'Unfortunately we won\'t be able to squeeze it in our schedule :(...', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- -----------------------------------------------------
-- TRIP FILES
-- -----------------------------------------------------
INSERT INTO `trip_files`(`id`, `title`, `path`, `trip_id`, `created_at`, `updated_at`) VALUES
  (NULL, 'testfile1', 'testfile1.pdf', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'testfile2', 'testfile2.txt', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (NULL, 'testfile3', 'testfile3.jpg', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
