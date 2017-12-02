USE triphatch;

-- -----------------------------------------------------
-- ACTION TYPES
-- -----------------------------------------------------
INSERT INTO `action_types` (`id`, `name`, `icon_class`, `color_class`) VALUES
  (NULL, 'Bus', 'fa-bus', 'transport'),
  (NULL, 'Plane', 'fa-plane', 'transport'),
  (NULL, 'Train', 'fa-train', 'transport'),
  (NULL, 'Car', 'fa-car', 'transport'),
  (NULL, 'Important', 'fa-star', 'important'),
  (NULL, 'Point', 'fa-map-marker', 'interest'),
  (NULL, 'Drink', 'fa-glass', 'food'),
  (NULL, 'Food', 'fa-cutlery', 'food'),
  (NULL, 'Coffee', 'fa-coffee', 'food'),
  (NULL, 'Accommodation', 'fa-bed', 'accommodation'),
  (NULL, 'Information', 'fa-info', 'other');

-- -----------------------------------------------------
-- IMAGES
-- -----------------------------------------------------
INSERT INTO `images` (`id`, `path`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (1, 'avatar-default.jpg', 'default user image', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (2, 'mountains.jpg', 'default trip and action image', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

-- -----------------------------------------------------
-- USERS
-- -----------------------------------------------------
INSERT INTO `users` (`id`, `email`, `password`, `display_name`, `is_admin`, `image_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (NULL, 'testemail1@test.test', 'definitelynotrealpasswordhash', 'Test User 1', '1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'testemail2@test.test', 'definitelynotrealpasswordhash', NULL, '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'testemail3@test.test', 'definitelynotrealpasswordhash', 'Test User 3', '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'testemail4@test.test', 'definitelynotrealpasswordhash', NULL, '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'testemail5@test.test', 'definitelynotrealpasswordhash', 'Test User 5', '0', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

-- -----------------------------------------------------
-- TRIPS

-- -----------------------------------------------------
INSERT INTO `trips` (`id`, `title`, `start_date`, `end_date`, `ended`, `image_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (NULL, 'Test trip 1', NULL, NULL, 0, '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Test trip 2', NULL, NULL, 1, '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Test trip 3', NULL, NULL, 0, '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

-- -----------------------------------------------------
-- USER TRIP CROSS REFERENCE
-- -----------------------------------------------------
INSERT INTO `user_trip_xref` (`id`, `user_id`, `trip_id`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (NULL, '1', '1', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '2', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '3', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '4', '1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '5', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '1', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '3', '2', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '5', '2', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '2', '3', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '3', '3', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '4', '3', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, '5', '3', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

-- -----------------------------------------------------
-- DAYS
-- -----------------------------------------------------
INSERT INTO `days` (`id`, `title`, `order`, `image_id`, `trip_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (NULL, 'Day 1', '0', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Day 2', '1', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Day 3', '2', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Day 1', '0', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Day 2', '1', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Day 3', '2', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Day 4', '3', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Day 5', '4', '2', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

-- -----------------------------------------------------
-- ACTIONS
-- -----------------------------------------------------
INSERT INTO `actions`(`id`, `title`, `content`, `order`, `day_id`, `action_type_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (NULL, 'Arrival at Some Random Airport (SRA) - 9:35', 'Action content', '1', '1', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Train ride from Random Station 1 to Random Station 2 - 10:30 to 12:15', 'Action content', '2', '1', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Registering for Random National Park permits', 'Action content', '3', '1', '5', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Random interesting historic building', 'Action content', '4', '1', '6', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Lunch at a random cool place', 'Action content', '5', '1', '8', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Air-bnb in a cool beach house', 'Action content', '6', '1', '10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Action 1', 'Action content', '1', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Action 2', 'Action content', '2', '2', '6', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Action 3', 'Action content', '3', '2', '10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Action 1', 'Action content', '1', '3', '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

-- -----------------------------------------------------
-- TRIP COMMENTS
-- -----------------------------------------------------
INSERT INTO `trip_comments`(`id`, `content`, `user_trip_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (NULL, 'So what do you think guys?', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'I would rather eat burgers than italian food on day 2...', 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Yea, good idea!', 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Sure, I\'ll change the plan accordingly.', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'And what about visiting a ZOO on day 3?', 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL),
  (NULL, 'Unfortunately we won\'t be able to squeeze it in our schedule :(...', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);