ALTER TABLE case_fiscale ADD COLUMN tip_casa VARCHAR(50) NOT NULL DEFAULT 'FiscalDatecs' AFTER nume_casa;
ALTER TABLE case_fiscale ADD COLUMN cale_fisiere VARCHAR(255) NOT NULL DEFAULT 'C:/xampp/htdocs/bonuri/' AFTER tip_casa;
