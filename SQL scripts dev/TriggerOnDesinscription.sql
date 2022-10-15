create definer = root@localhost trigger changeEtatOnDesinscription
    before DELETE
    on inscription
    for each row
    CALL isSortieNotFull(OLD.sortie_id_id);

