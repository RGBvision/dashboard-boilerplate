DROP TABLE IF EXISTS `thumbnails`;
CREATE TABLE IF NOT EXISTS `thumbnails` (
                                            `size` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                            `width` smallint(5) unsigned DEFAULT NULL,
                                            `height` smallint(5) unsigned DEFAULT NULL,
                                            UNIQUE KEY `size` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `thumbnails`;

INSERT INTO `thumbnails` (`size`, `width`, `height`) VALUES
('big', 800, 600),
('med', 640, 480),
('min', 320, 240),
('mic', 150, 150);
