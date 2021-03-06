DROP VIEW IF EXISTS Student_Conduct_v3;

CREATE ALGORITHM=UNDEFINED DEFINER=fakeadmin@`%.fake.umas.edu` SQL SECURITY DEFINER VIEW Student_Conduct_v3 AS 
SELECT  sql_no_cache std.PSID AS empl_id,
        std.LastName AS last_name,
        IFNULL(spn.FirstName, std.FirstName) AS first_name,
        if((std.MI = _latin1''), NULL, std.MI) AS middle_initial,
        std.FullName AS full_name,
        std.Gender AS sex,
        std.Birth AS birthdate,
        if((cast(curdate() as datetime) < (cast(std.Birth as datetime) + interval 18 year)), _latin1'Y', _latin1'N') AS under_18_flag,
        if((cast(curdate() as datetime) >= (cast(std.Birth as datetime) + interval 21 year)), _latin1'Y', _latin1'N') AS over_21_flag,
        std.PrivacyCode AS ferpa_flag,
        std.admit_term AS admit_term,
        if((isnull(std.admit_term) or (std.admit_term = _latin1'')), _latin1'No Data',
        concat(if((substr(std.admit_term, 4, 1) = _latin1'7'), _latin1'Fall ', _latin1''),
        if((substr(std.admit_term, 4, 1) = _latin1'1'), _latin1'Int-Ses ', _latin1''),
        if((substr(std.admit_term, 4, 1) = _latin1'3'), _latin1'Spring ', _latin1''),
        if((substr(std.admit_term, 4, 1) = _latin1'5'), _latin1'Summer ', _latin1''),
        if((substr(std.admit_term, 1, 1) = _latin1'0'), _latin1'19', _latin1'20'),
        substr(std.admit_term, 2,       2))) AS admit_term_descr,
        std.expect_grad_term AS expect_grad_term,
        if((isnull(std.expect_grad_term) or (std.expect_grad_term = _latin1'')), _latin1'No Data', concat(if((substr(std.expect_grad_term, 4, 1) = _latin1'7'), _latin1'Fall ', _latin1''),
        if((substr(std.expect_grad_term, 4, 1) = _latin1'1'), _latin1'Int-Ses ',        _latin1''),
        if((substr(std.expect_grad_term, 4, 1) = _latin1'3'), _latin1'Spring ', _latin1''),
        if((substr(std.expect_grad_term, 4, 1) = _latin1'5'), _latin1'Summer ', _latin1''),
        if((substr(std.expect_grad_term, 1, 1) = _latin1'0'), _latin1'19', _latin1'20'),
        substr(std.expect_grad_term, 2, 2))) AS expect_grad_term_descr,
        std.prim_acad_career AS prim_acad_career,
        std.prim_acad_prog AS prim_acad_prog,
        std.prim_acad_prog_status AS prim_acad_prog_status,
        apst1.tlt_long_name AS prim_acad_prog_status_descr,
        std.prim_acad_plan AS prim_acad_plan,
        std.Building AS curr_assign_building,
        std.Room AS curr_assign_room,
        std.Street AS mail_address1,
        std.AddStreet AS mail_address2,
        std.City AS mail_city,
        std.State AS mail_state_prov,
        std.Zip AS mail_postal_code,
        std.PStreet AS perm_address1,
        std.PAddStreet AS perm_address2,
        std.PCity AS perm_city,
        std.PState AS perm_state_prov,
        std.PZip AS perm_postal_code,
        std.PPhone AS perm_phone,
        std.Phone AS local_phone,
        std.Email AS oit_email,
        if(((std.um_athl_part1 = _latin1'TEAM') or (std.um_athl_part2 = _latin1'TEAM') or (std.um_athl_part3 = _latin1'TEAM')), _latin1'Y', _latin1'N') AS Athlete,
        concat(if((isnull(std.um_sport1) or (std.um_sport1 = _latin1'')), _latin1'', std.um_sport1),
        if((isnull(std.um_sport2) or (std.um_sport2 = _latin1'')), _latin1'', concat(_latin1',', std.um_sport2)),
        if((isnull(std.um_sport3) or (std.um_sport3 = _latin1'')), _latin1'', concat(_latin1',', std.um_sport3))) AS Sport,
        if(((std.Building <> _latin1'') and (std.Building is not null)), _latin1'Y', _latin1'N') AS on_campus,
        if((((std.umh_position1 in (_latin1'RA', _latin1'EMEN', _latin1'TMEN')) and (std.umh_position_eff_status1 = _latin1'A')) or ((std.umh_position2 in (_latin1'RA', _latin1'EMEN', _latin1'TMEN')) and (std.umh_position_eff_status2 = _latin1'A'))), _latin1'Y', _latin1'N') AS RA_PM,
        std.last_updated_dttm AS last_updated_dttm
FROM Student std
LEFT OUTER JOIN acad_prog_status_tlt apst1 ON std.prim_acad_prog_status = apst1.value
LEFT OUTER JOIN Student_Pf2_Name spn ON std.PSID = spn.PSID;

GRANT SELECT ON Student_Conduct_v3 TO fake_user@`%.fake.umass.edu`;
