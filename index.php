 <?php 
    include "db.php";
        
    // исходная строка
    $input_string = '{Пожалуйста,|Просто|Если сможете,} сделайте так, чтобы это {удивительное|крутое|простое|важное|бесполезное} тестовое предложение {изменялось {быстро|мгновенно|оперативно|правильно} случайным образом|менялось каждый раз}';

    // глобальная переменная для количества всевозможных комбинаций
    $count_of_combinations = 1;
    
    // callback-функция, которая генерирует случайные комбинации
    function generate_random_combinations($input_mas)
    {
        $r = $input_mas[1];
        if (strpos($r, "|") === false) return $r;
      
        // разбиение строки на массив строк с помощью разделителя "|"
        $variants = explode("|", $r);
      
        // занесение числа вариантов в глобальную переменную
        $GLOBALS['count_of_combinations'] *= count($variants);
      
        // возвращение массива случайных комбинаций
        return $variants[array_rand($variants)];
    }
    
    // функция получения новой строки из комбинаций путём поиска 
    // по регулярному выражению и замены с использованием callback-функции 
    // generate_random_combinations
    function get_new_result_string($input_str)
    {
        while(strpos($input_str, "{") !== false)
        {
            // поиск подстроки по регулярному выражению с последующей заменой
            $input_str = preg_replace_callback(
                '/{([^{}]+)}/',
                'generate_random_combinations',
                $input_str
            );
        }
     
        return $input_str;
    }
    
    $exit = 0;
    // получение первой строки из комбинаций
    $mass[0] = get_new_result_string($input_string);
    $i = 0;
         
    // количество всевозможных комбинаций
    $c = $count_of_combinations;
    
    // заполнение массива mass случайными строками из комбинаций
    while ($exit < $c)
    {
        $res = get_new_result_string($input_string);
        $j = 0;
        foreach ($mass as $v) 
        {
            // проверка на наличие новой строки из комбинаций в массиве строк mass
            if (strcasecmp($mass[$j], $res) != 0)
            {
                $i++;
                $mass[$i] = $res;
         	 	$exit++;
                break;
            }
            $j++;
        }
    }
        
    // подключение к базе данных
    $db_connect = new database();
    
    // поиск похожих значений в таблице strings
    foreach ($mass as $val)
    {
        $check_value = mysql_query("SELECT * FROM strings WHERE (value = '$val') LIMIT 1");
        if (mysql_num_rows($check_value) == null)
        {
            echo $val;
            $query_insert_new_value = "INSERT INTO strings (value) VALUES ('$val')";
            mysql_query($query_insert_new_value) or die(mysql_error());
        }
    }
    
    mysql_close($db_connect->get_link());
?>




