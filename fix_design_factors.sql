-- Fix for design_factors table - Add AUTO_INCREMENT
-- Run this query in your database if the table already exists

ALTER TABLE `design_factors` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
