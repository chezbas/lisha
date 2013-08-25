<?php
    $query = "
                SELECT
                -- STARTBODY
				   				`transaction`.`index`			AS `index`,
				   				`transaction`.`daterec` 		AS `daterec`,
				   				`transaction`.`description`	    AS `description`,
				   				`transaction`.`amount`			AS `amount`,
				   				IF(MOD(`transaction`.`index`,2)=0,MD5(`transaction`.`amount`),SHA1(`transaction`.`amount`))	    AS `encrypt`,
				   				CONCAT('[b]',`transaction`.`status`,'[/b]')			AS `status`,
				   				`transaction`.`checkme`		    AS `checkme`,
				   				`transaction`.`datum`			AS `datum`,
				   				`transaction`.`mode`			AS `mode`,
				   				`TRAN2`.`text`			        AS `text`,
				   				`transaction`.`status`			AS `MyGroupTheme`
				 -- ENDBODY
				   			FROM
				   				`transaction`, -- No alias on update table !!
				   				`transaction2` `TRAN2`
				   			WHERE 1 = 1
				   					AND `transaction`.`mode` = `TRAN2`.`mode`
            ";

    $query_final_pos = strripos($query, 'FROM');
    $query_final =  'SELECT DISTINCT '.'`transaction`.`description`	    AS `description` '.substr($query,$query_final_pos);
echo nl2br($query);
echo '<hr>';
echo nl2br($query_final);
