create
definer = root@localhost procedure isSortieNotFull(IN idSortie int)
BEGIN
    DECLARE max INT;
    DECLARE inscrits INT;
    DECLARE dateC DATE;

begin
SELECT s.nb_inscriptions_max, count(*), s.date_cloture INTO max, inscrits, dateC FROM sortie s
    INNER JOIN inscription i
ON s.id = i.sortie_id_id
WHERE s.id = idSortie ;
IF inscrits < max AND dateC < NOW() THEN
UPDATE sortie SET etats_no_etat_id = 2 WHERE id = idSortie;
END IF;
end;
END;

