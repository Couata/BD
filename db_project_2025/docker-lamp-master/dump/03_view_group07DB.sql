USE group7;

-- View for horaires
CREATE OR REPLACE VIEW view_horaires_details AS
SELECT 
    h.HEURE_ARRIVEE,
    h.HEURE_DEPART,
    a.NOM AS NOM_ARRET,
    i.NOM AS NOM_ITINERAIRE,
    t.DIRECTION,
    ag.NOM AS NOM_AGENCE,
    s.NOM AS NOM_SERVICE
FROM HORRAIRE h
JOIN ARRET a ON h.ARRET_ID = a.ID
JOIN TRAJET t ON h.TRAJET_ID = t.TRAJET_ID
JOIN ITINERAIRE i ON t.ITINERAIRE_ID = i.ID
JOIN AGENCE ag ON i.AGENCE_ID = ag.ID
JOIN SERVICE s ON t.SERVICE_ID = s.ID;

-- View for exceptions
CREATE OR REPLACE VIEW view_exceptions_details AS
SELECT 
    ej.CODE,
    s.NOM AS NOM_SERVICE,
    i.NOM AS NOM_ITINERAIRE,
    s.DATE_DEBUT,
    s.DATE_FIN
FROM EXCEPTION_JOUR ej
JOIN SERVICE s ON ej.SERVICE_ID = s.ID
JOIN TRAJET t ON s.ID = t.SERVICE_ID
JOIN ITINERAIRE i ON t.ITINERAIRE_ID = i.ID;

-- View for expanded services per date
CREATE OR REPLACE VIEW dates_services AS
WITH RECURSIVE dates_services_temp AS (
    SELECT s.ID AS service_id, s.NOM AS nom_service, s.DATE_DEBUT AS date_actuelle, s.DATE_FIN AS date_fin
    FROM SERVICE s
    UNION ALL
    SELECT dst.service_id, dst.nom_service, DATE_ADD(dst.date_actuelle, INTERVAL 1 DAY), dst.date_fin
    FROM dates_services_temp dst
    WHERE dst.date_actuelle < dst.date_fin
)
SELECT dst.service_id, dst.nom_service, dst.date_actuelle
FROM dates_services_temp dst
JOIN SERVICE s ON s.ID = dst.service_id
WHERE 
    (DAYOFWEEK(dst.date_actuelle) = 1 AND s.DIMANCHE = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 2 AND s.LUNDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 3 AND s.MARDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 4 AND s.MERCREDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 5 AND s.JEUDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 6 AND s.VENDREDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 7 AND s.SAMEDI = 1);


-- View for valid services considering exceptions
CREATE OR REPLACE VIEW dates_services_exception AS
SELECT ds.date_actuelle, ds.nom_service
FROM dates_services ds
LEFT JOIN EXCEPTION_JOUR e ON ds.service_id = e.SERVICE_ID AND ds.date_actuelle = e.DATE_EXCEPTION
WHERE (e.CODE IS NULL OR e.CODE = 1) AND NOT (e.CODE = 2);

-- Final view: grouped services per date
CREATE OR REPLACE VIEW services_par_date_final AS
SELECT 
    ds.date_actuelle AS date_service,
    GROUP_CONCAT(nom_service ORDER BY nom_service) AS services_actifs
FROM dates_services_exception ds
GROUP BY ds.date_actuelle
ORDER BY ds.date_actuelle;
