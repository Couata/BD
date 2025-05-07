USE group7

DELIMITER $$
    -- check if the inserted entity is on the belgian bounding box
    CREATE TRIGGER check_coords_belgium
    BEFORE INSERT ON ARRET
    FOR EACH ROW
    BEGIN
        IF NOT (NEW.LATITUDE BETWEEN 49.5294835476 AND 51.4750237087) OR
            NOT (NEW.LONGITUDE BETWEEN 2.51357303225 AND 6.15665815596) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Les coordonnées doivent être en Belgique.';
        END IF;
    END$$

    -- check if the updated entity is in the belgian bounding box
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

    -- on update on Arret change all dependecies linked to it
    CREATE TRIGGER update_arret_id_dependencies
    AFTER UPDATE ON ARRET
    FOR EACH ROW
    BEGIN
        IF OLD.ID != NEW.ID THEN
            UPDATE ARRET_DESSERVI
            SET ARRET_ID = NEW.ID
            WHERE ARRET_ID = OLD.ID;

            UPDATE HORRAIRE
            SET ARRET_ID = NEW.ID
            WHERE ARRET_ID = OLD.ID;
        END IF;
    END$$

    -- check if the inserted times are logical or not
    CREATE TRIGGER check_arrival_before_departure
    BEFORE INSERT ON HORRAIRE
    FOR EACH ROW
    BEGIN
        IF NEW.HEURE_ARRIVEE >= NEW.HEURE_DEPART THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'arrivée doit être avant le départ.';
        END IF;
    END$$

DELIMITER;