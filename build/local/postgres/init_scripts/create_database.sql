-- The main laravel database will be created when the container starts. We add a test db here to save headaches later
-- This script will only be run the first time the volume is created for postgres. If you need to run it, remove the volume
-- but you will lose data in your local database if you do this.
CREATE DATABASE laravel_test;