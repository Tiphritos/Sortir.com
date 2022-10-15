create definer = root@localhost trigger changeEtatOnInscription
    before INSERT
    on inscription
    for each row
    CALL isSortieFull(NEW.sortie_id);

