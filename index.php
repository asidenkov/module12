

<h2>Разделение ФИО на части</h2>

<?php
function getPartsFromFullname($fullname) {
    // Разбиваем строку на части по пробелу
    $parts = explode(' ', $fullname);
    
    // Возвращаем массив с ключами 'surname', 'name', 'patronomyc'
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronomyc' => $parts[2]
    ];
}

// Пример использования
$fullname = 'Алексеев Сергей Васильевич';
$result = getPartsFromFullname($fullname);

print_r($result);

echo "<br>";

?>
<h2>Объединение ФИО из частей</h2>


<?php
function getFullnameFromParts($surname, $name, $patronomyc) {
    // Склеиваем строки через пробел
    return trim("$surname $name $patronomyc");
}

// Пример использования
$surname = 'Сергеев';
$name = 'Михаил';
$patronomyc = 'Дмитриевич';

$fullname = getFullnameFromParts($surname, $name, $patronomyc);
echo $fullname;


echo "<br>";

?>

<h2>Сокращение фамилии</h2>

<?php
// Функция для сокращенного имени
function getShortName($fullname) {
    // Разбиваем полное имя на части
    $parts = getPartsFromFullname($fullname);
    
    // Формируем строку "Имя Ф."
    return $parts['name'] . ' ' . mb_substr($parts['surname'], 0, 1) . '.';
}

// Пример использования
$shortName = getShortName('Борисов Виталий Игоревич');
echo $shortName;


echo "<br>";

?>



<h2>Определение пола</h2>

<?php
// Функция для определения пола
function getGenderFromName($fullname) {
    // Разбиваем ФИО на части
    $parts = getPartsFromFullname($fullname);

    // Изначально суммарный признак пола равен 0
    $genderScore = 0;

    // Признаки мужского пола
    if (mb_substr($parts['patronomyc'], -2) === 'ич') {
        $genderScore++;
    }
    if (mb_substr($parts['name'], -1) === 'й' || mb_substr($parts['name'], -1) === 'н') {
        $genderScore++;
    }
    if (mb_substr($parts['surname'], -1) === 'в') {
        $genderScore++;
    }

    // Признаки женского пола
    if (mb_substr($parts['patronomyc'], -3) === 'вна') {
        $genderScore--;
    }
    if (mb_substr($parts['name'], -1) === 'а') {
        $genderScore--;
    }
    if (mb_substr($parts['surname'], -2) === 'ва') {
        $genderScore--;
    }

    // Определение пола
    if ($genderScore > 0) {
        return 1; // Мужской пол
    } elseif ($genderScore < 0) {
        return -1; // Женский пол
    } else {
        return 0; // Неопределенный пол
    }
}

// Пример использования
$fullname1 = 'Шварцнегер Арнольд Густавович';
$fullname2 = 'Липницкая Аделия Максимовна';
$fullname3 = 'аль-Хорезми Мухаммад ибн-Муса';

echo getGenderFromName($fullname1); 
echo "<br>";
echo getGenderFromName($fullname2);
echo "<br>";
echo getGenderFromName($fullname3);

?>

<h2>Определение процента полового состава</h2>

<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Липницкая Аделия Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


// Функция для определения полового состава аудитории
function getGenderDescription($persons) {
    $maleCount = 0;
    $femaleCount = 0;
    $undefinedCount = 0;

    // Проходим по всем элементам массива
    foreach ($persons as $person) {
        $gender = getGenderFromName($person['fullname']);
        
        // Увеличиваем счетчики в зависимости от пола
        if ($gender === 1) {
            $maleCount++;
        } elseif ($gender === -1) {
            $femaleCount++;
        } else {
            $undefinedCount++;
        }
    }

    // Общее количество людей
    $totalCount = count($persons);

    // Вычисляем проценты и округляем до одного знака после запятой
    $malePercentage = round(($maleCount / $totalCount) * 100, 1);
    $femalePercentage = round(($femaleCount / $totalCount) * 100, 1);
    $undefinedPercentage = round(($undefinedCount / $totalCount) * 100, 1);

    // Формируем строку с результатами
    return "Гендерный состав аудитории:<br>---------------------------<br>" .
            "Мужчины - $malePercentage%<br>" .
            "Женщины - $femalePercentage%<br>" .
            "Не удалось определить - $undefinedPercentage%";
};


echo getGenderDescription($example_persons_array);

?>