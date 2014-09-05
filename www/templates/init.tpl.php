<?php
defined('sCore') or die('access denied');
class template {
VAR $files = array();
VAR $variables = array();
VAR $opening_escape = '{';
VAR $closing_escape = '}';


function set_tpl($tpl) {
$this->tpl=$tpl;
}

function register_file($file_id, $file_name) {
// Открыть $file_name для чтения или завершить программу
// с выдачей сообщения об ошибке.
$fh = fopen($file_name, "r") or die("Couldn't open $file_name!");
// Прочитать все содержимое файла $file_name в переменную.
$file_contents = fread($fh, filesize($file_name));
// Присвоить содержимое элементу массива
// с ключом $file_id
$this->files[$file_id] = $file_contents;
// Работа с файлом завершена, закрыть его.
fclose($fh);
}
function register_variables($file_id, $variable_name) {
// Попытаться создать массив,
// содержащий переданные имена переменных
$input_variables = explode(",", $variable_name);
// Перебрать имена переменных
while (list($key,$value) = each($input_variables)) :
// Присвоить значение очередному элементу массива $this->variables 
$this->variables[$file_id][] = $value;
endwhile;
}

function file_parser($file_id) {
// Сколько переменных зарегистрировано для данного файла?
$varcount = count($this->variables[$file_id]);
// Сколько файлов зарегистрировано?
$keys = array_keys($this->files);
// Если файл $file_id существует в массиве $this->files
// и с ним связаны зарегистрированные переменные
if ( (in_array($file_id, $keys)) && ($varcount > 0) ) :
// Сбросить $x 
$x = 0;
// Пока остаются переменные для обработки...
while ($x < sizeof($this->variables[$file_id])) :
// Получить имя очередной переменной 
$string = $this->variables[$file_id][$x];
// Получить значение переменной. Обратите внимание:
// для получения значения используется конструкция $$.
// Полученное значение подставляется в файл вместо
// указанного имени переменной.
GLOBAL $$string;
// Построить точный текст замены вместе с ограничителями
$needle = $this->opening_escape.$string.$this->closing_escape;
// Выполнить замену.
$this->files[$file_id] = str_replace( $needle,$$string,$this->files[$file_id]);
// Увеличить $х 
$x++;
endwhile;
endif;
}

// Обработчик меню
function set_menu ($menu, $url){
GLOBAL $$menu;
$this->register_file($menu, $url);
$$menu = $this->get_file($menu);
}

// Обработчик страниц
function sec_pgs($pgs) {
GLOBAL $authorized;
if (!$authorized) {
$this->sec_pgs = explode(",", $pgs);
}
}

function set_content() {
GLOBAL $content;
if (isset($_GET['page'])) {
if (is_array($this->sec_pgs) && in_array($_GET['page'], $this->sec_pgs)){
$content=PGS_DIR."access_denied.html";
} else {
$content = PGS_DIR.$_GET['page'].".html";

if (!file_exists($content)){
$content = PGS_DIR.$_GET['page'].".php";
if (!file_exists($content))
$content = '404.html';
}
}
} else {
GLOBAL $slider;
$this->register_file('slider', 'parts/slider.html');
$slider = $this->get_file('slider');
$content = PGS_DIR.'home.html';}

$this->register_file('content', $content);
$content = $this->get_file('content');
$this->register_variables($this->tpl,'content');
}

// Методы вывода файлв
function print_file($file_id) {
// Вывести содержимое файла с идентификатором $file_id
print $this->files[$file_id];
}
function eval_file($file_id) {
// Вывести содержимое файла с идентификатором $file_id
eval (' ?' . '>' . $this->files[$file_id] . '<' . '?php ');
}
function get_file($file_id) {
return $this->files[$file_id];
}
}
?>