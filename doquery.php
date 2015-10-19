<?php

   		if(isset($_POST['most1']) && !empty($_POST['most1'])) {

            $data = $_POST['most1'];
            $sumName = pg_escape_string($_POST['sumName']);
            var_dump($data);

            $db = pg_connect('host=localhost dbname=myDB'); 

            //insert the summoner preferences to database
            foreach ($data as $key => $value) {

                    $champ = pg_escape_string($key);
                    $rating = $value;


                    $checkExistQuery = "SELECT COUNT(*) FROM summtable WHERE summoner='" . $sumName . "' AND item='" . $champ . "'";

                    $query = "INSERT INTO summtable(summoner, item, rating) 
                                   VALUES('" . $sumName . "', '" . $champ . "', '" . $rating . "')";
                
                    $result = pg_query($db, $checkExistQuery); 
                    if (!$result) { 
                        $errormessage = pg_last_error(); 
                        echo "Error with query: " . $errormessage; 
                        exit(); 
                    } 
                    $arr = pg_fetch_array($result, 0, PGSQL_NUM);
                    if ($arr[0]==1){
                        $query = "UPDATE summtable SET item= '" . $champ . "', rating= '" . $rating . "' WHERE summoner='" . $sumName . "' AND item='" . $champ . "'";
                    }

                    $result2 = pg_query($db, $query); 
                    if (!$result2) { 
                        $errormessage = pg_last_error(); 
                        echo "Error with query: " . $errormessage; 
                        exit(); 
                    } 
            
            }
             printf ("This summoner was added to the table - %s", $arr[0]);


             //yield the recommendation
            foreach ($data as $key => $value) {

                $champ = pg_escape_string($key);

                $query2 = "SELECT champB FROM champsim WHERE champA='" . $champ . "' ORDER BY pearson_score DESC";
                    
                $result3 = pg_query($db, $query2); 
                if (!$result3) { 
                    $errormessage = pg_last_error(); 
                    echo "Error with query: " . $errormessage; 
                    exit(); 
                } 

                $arr = pg_fetch_array($result3, 0, PGSQL_NUM);
                printf ("This recommendation is made - %s", $arr[0]);
                $arr = pg_fetch_array($result3, NULL, PGSQL_NUM);
                printf ("This recommendation is made - %s", $arr[0]);
                

            }

            pg_close(); 
    }

?>  