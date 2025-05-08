USE group7;

DELIMITER $$

-- Trigger: Check coordinates are in Belgium
CREATE TRIGGER check_coords_belgium_update
BEFORE UPDATE ON ARRET
FOR EACH ROW
BEGIN
    IF NOT (NEW.LATITUDE BETWEEN 49.5294835476 AND 51.4750237087) OR
       NOT (NEW.LONGITUDE BETWEEN 2.51357303225 AND 6.15665815596) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Les coordonnées doivent être en Belgique.';
    END IF;
END$$

-- Trigger: Update dependent tables if ARRET ID changes
CREATE TRIGGER update_arret_id_dependencies
AFTER UPDATE ON ARRET
FOR EACH ROW
BEGIN
    IF OLD.ID != NEW.ID THEN
        UPDATE ARRET_DESSERVI SET ARRET_ID = NEW.ID WHERE ARRET_ID = OLD.ID;
        UPDATE HORRAIRE SET ARRET_ID = NEW.ID WHERE ARRET_ID = OLD.ID;
    END IF;
END$$

-- Trigger: Ensure arrival time is after departure
CREATE TRIGGER check_arrival_before_departure
BEFORE INSERT ON HORRAIRE
FOR EACH ROW
BEGIN
    IF NEW.HEURE_ARRIVEE <= NEW.HEURE_DEPART THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'arrivée doit être après le départ.';
    END IF;
END$$

-- Trigger: Prevent deletion if it would leave <2 stops
CREATE TRIGGER prevent_less_than_two_arrets
BEFORE DELETE ON ARRET_DESSERVI
FOR EACH ROW
BEGIN
    DECLARE remaining_arrets INT;

    SELECT COUNT(*) INTO remaining_arrets
    FROM ARRET_DESSERVI
    WHERE ITINERAIRE_ID = OLD.ITINERAIRE_ID;

    IF remaining_arrets <= 2 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un itinéraire doit avoir au moins deux arrêts.';
    END IF;
END$$

DELIMITER ;
