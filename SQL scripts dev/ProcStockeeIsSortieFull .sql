create
    definer = root@localhost procedure isSortieFull(IN idSortie int)
BEGIN
    DECLARE finished INT default 0;
    DECLARE max INT;
    DECLARE inscrits INT;

    begin
        SELECT s.nb_inscriptions_max, count(*) INTO max, inscrits FROM sortie s
                                                INNER JOIN inscription i
                                                ON s.id = i.sortie_id_id
        WHERE s.id = idSortie ;
        IF inscrits >= max  THEN
            UPDATE sortie SET etats_no_etat_id = 3 WHERE id = idSortie;
        END IF;
    end;
END;




