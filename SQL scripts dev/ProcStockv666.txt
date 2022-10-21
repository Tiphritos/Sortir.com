create
definer = root@localhost procedure changerEtat()
BEGIN
    DECLARE id_1 INT;
    DECLARE id_2 INT;
    DECLARE id_3 INT;
    DECLARE id_4 INT;
    
    DECLARE finished_1 INT default 0;
    DECLARE finished_2 INT default 0;
    DECLARE finished_3 INT default 0;
    DECLARE finished_4 INT default 0;

    DECLARE sortiesOuvToClo CURSOR FOR
SELECT id FROM sortie
WHERE date_cloture <= NOW() AND etats_no_etat_id = 2;
DECLARE sortiesCloToEC CURSOR FOR
SELECT id FROM sortie
WHERE date_debut <= NOW() AND etats_no_etat_id = 3;
DECLARE sortiesEcToPas CURSOR FOR
SELECT id FROM sortie
WHERE DATE_ADD(date_debut, INTERVAL duree MINUTE) <= NOW() AND etats_no_etat_id = 4;
DECLARE sortiesPasToArc CURSOR FOR
SELECT id FROM sortie
WHERE DATE_ADD(DATE_ADD(date_debut, INTERVAL duree MINUTE), INTERVAL 1 MONTH) <= NOW()
  AND (etats_no_etat_id = 5 OR etats_no_etat_id = 6);

#Changer l'etat de Ouverte à Clôturée
    begin
        DECLARE CONTINUE HANDLER
            FOR NOT FOUND SET finished_1 = 1;
        getSorties: LOOP
            OPEN sortiesOuvToClo;
            FETCH sortiesOuvToClo INTO id_1;
            IF finished_1 = 1 THEN
                LEAVE getSorties;
            END IF;

            UPDATE sortie SET etats_no_etat_id = 3 WHERE id = id_1;
            CLOSE sortiesOuvToClo;
        END LOOP getSorties;
    end;

    #Changer l'etat de Clôturée à En cours
begin
        DECLARE CONTINUE HANDLER
            FOR NOT FOUND SET finished_2 = 1;
        getSortiesEC: LOOP
            OPEN sortiesCloToEC;
FETCH sortiesCloToEC INTO id_2;
IF finished_2 = 1 THEN
                LEAVE getSortiesEC;
END IF;

UPDATE sortie SET etats_no_etat_id = 4 where id = id_2;
CLOSE sortiesCloToEC;
END LOOP getSortiesEC;
end;

    #Changer l'etat de En Cours à Clôturée
    begin
        DECLARE CONTINUE HANDLER
            FOR NOT FOUND SET finished_3 = 1;
        getSortiesPas: LOOP
            OPEN sortiesEcToPas;
            FETCH sortiesEcToPas INTO id_3;
            IF finished_3 = 1 THEN
                LEAVE getSortiesPas;
            END IF;

            UPDATE sortie SET etats_no_etat_id = 5 where id = id_3;
            CLOSE sortiesEcToPas;
        END LOOP getSortiesPas;
    end;

    #Changer l'etat de Cloturée à Archivée
begin
        DECLARE CONTINUE HANDLER
            FOR NOT FOUND SET finished_4 = 1;
        getSortiesArc: LOOP
            OPEN sortiesPasToArc;
FETCH sortiesPasToArc INTO id_4;
IF finished_4 = 1 THEN
                LEAVE getSortiesArc;
END IF;

UPDATE sortie SET etats_no_etat_id = 7 where id = id_4;
END LOOP;
end;
END;

