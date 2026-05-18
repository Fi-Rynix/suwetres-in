CREATE DATABASE fatigue_system;
USE fatigue_system;

CREATE TABLE hasil_analisis (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    
    jam_tidur INT NOT NULL,
    jumlah_tugas INT NOT NULL,
    aktivitas_organisasi INT NOT NULL,
    screen_time INT NOT NULL,
    
    nilai_fatigue DOUBLE NOT NULL,
    status VARCHAR(50) NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
    ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO hasil_analisis 
(jam_tidur, jumlah_tugas, aktivitas_organisasi, screen_time, nilai_fatigue, status)
VALUES
(4, 8, 7, 10, 80, 'Kelelahan Tinggi'),
(7, 3, 2, 5, 25, 'Kelelahan Ringan'),
(6, 5, 4, 8, 50, 'Kelelahan Sedang');

SELECT * FROM hasil_analisis;