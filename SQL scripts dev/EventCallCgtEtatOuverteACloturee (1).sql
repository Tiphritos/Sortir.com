CREATE event every_day_data_insert on schedule
    every 1 MINUTE
        starts '2022-10-11 16:37:22'
    on completion preserve
    enable
    do
    BEGIN
CALL `changerEtat`;   -- from 12:30 AM to 5:30 AM
END;

