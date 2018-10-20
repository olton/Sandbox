<?php

namespace Classes;

class Calculator
{
    var $_priors = array(
        '('  => 0 , // Левая скобка
        ')'  => 1 , // Правая скобка
        ';'  => 2 , // Добавление аргумента функции
        '='  => 3 , // Сравнение - "равно"
        '<'  => 3 , // Сравнение - "меньше"
        '>'  => 3 , // Сравнение - "больше"
        '<=' => 3 , // Сравнение - "меньше или равно"
        '>=' => 3 , // Сравнение - "больше или равно"
        '<>' => 3 , // Сравнение - "не равно"
        '&'  => 4 , // Конкатенация строк
        '+'  => 5 , // Сложение
        '-'  => 5 , // Вычитание
        '*'  => 6 , // Умножение
        '/'  => 6 , // Деление
        '^'  => 7 , // Возведение в степень
        '%'  => 8 , // Процент
        '-m' => 9 , // Унарный минус
        '+m' => 9 , // Унарный плюс
        ' '  => 10, // Пересечение диапазонов переменных
        ':'  => 11, // Ссылка на диапазон переменных
        'f'  => 12, // Функция
    );

    // Список функций с указанием, есть ли аргументы, и функции-обработчика
    var $_functions = array(
        'abs'       => array( // ABS - абсолютная величина числа
            'args'  => true,
            'func'  => '_abs'
        ),
        'acos'      => array( // ACOS - Arc cosine.
            'args'  => true,
            'func'  => '_acos'
        ),
        'acosh'     => array( // ACOSH - Inverse hyperbolic cosine.
            'args'  => true,
            'func'  => '_acosh'
        ),
        'acot'      => array( // ACOT - Возвращает арккотангенс числа.
            'args'  => true,
            'func'  => '_acot'
        ),
        'acoth'     => array( // ACOTH - Возвращает гиперболический арккотангенс
            'args'  => true,  // числа.
            'func'  => '_acoth'
        ),
        'and'       => array( // И - логическая функция &&
            'args'  => true,
            'func'  => '_and'
        ),
        'asin'      => array( // ASIN - Arc sine.
            'args'  => true,
            'func'  => '_asin'
        ),
        'asinh'     => array( // ASINH - Inverse hyperbolic sine.
            'args'  => true,
            'func'  => '_asinh'
        ),
        'atan'      => array( // ATAN - Arc tangent.
            'args'  => true,
            'func'  => '_atan'
        ),
        'atan2'     => array( // ATAN2 - Arc tangent of two variables.
            'args'  => true,
            'func'  => '_atan2'
        ),
        'atanh'     => array( // ATANH - Inverse hyperbolic tangent.
            'args'  => true,
            'func'  => '_atanh'
        ),
        'combin'    => array( // COMBIN(n;k) - число сочетаний из n по k без
            'args'  => true,  // повторений.
            'func'  => '_combin'
        ),
        'combina'   => array( // COMBINA(n;k) - число сочетаний из n по k с
            'args'  => true,  // повторениями.
            'func'  => '_combina'
        ),
        'cos'       => array( // COS - косинус числа
            'args'  => true,
            'func'  => '_cos'
        ),
        'cosh'      => array( // COSH - Hyperbolic cosine.
            'args'  => true,
            'func'  => '_cosh'
        ),
        'cot'       => array( // COT - Возвращает котангенс угла.
            'args'  => true,
            'func'  => '_cot'
        ),
        'coth'      => array( // COTH - гиперболический котангенс числа (угла).
            'args'  => true,
            'func'  => '_coth'
        ),
        'countblank'=> array( // COUNTBLANK - число пустых ячеек в диапазоне.
            'args'  => true,
            'func'  => '_countblank'
        ),
        'degrees'   => array( // DEGREES (ГРАДУСЫ) - Converts the radian number
            'args'  => true,  // to the equivalent number in degrees.
            'func'  => '_degrees'
        ),
        'even'      => array( // EVEN - Округляет положительное число в большую
            'args'  => true,  // сторону до ближайшего четного целого, а
            'func'  => '_even'// отрицательное число — в меньшую сторону до
        ),                    // ближайшего четного целого.
        'exp'       => array( // EXP - Calculates the exponent of e.
            'args'  => true,
            'func'  => '_exp'
        ),
        'fact'      => array( // FACT - Factorial.
            'args'  => true,
            'func'  => '_fact'
        ),
        'factdouble'=> array( // FACTDOUBLE (ДВФАКТР) - двойной факториал
            'args'  => true,
            'func'  => '_factDouble'
        ),
        'false'     => array( // FALSE (ЛОЖЬ) - возвращает false
            'args'  => false,
            'func'  => '_false'
        ),
        'gcd'       => array( // GCD (GCD_ADD, НОД) - The greatest common
            'args'  => true,  // divisor is the positive largest integer which
            'func'  => '_gcd' // will divide, without remainder, each of the
        ),                    // given integers.
        'gcd_add'   => array( // GCD (GCD_ADD, НОД) - The greatest common
            'args'  => true,  // divisor is the positive largest integer which
            'func'  => '_gcd' // will divide, without remainder, each of the
        ),                    // given integers.
        'if'        => array( // IF (ЕСЛИ) - условное выражение
            'args'  => true,
            'func'  => '_if'
        ),
        'int'       => array( // INT - Округляет число до ближайшего меньшего
            'args'  => true,  // целого.
            'func'  => '_int'
        ),
        'iseven'    => array( // ISEVEN - Возвращает значение "ИСТИНА" для
            'args'  => true,  // четных целых чисел и значение "ЛОЖЬ" — для
            'func'  => '_isEven'// нечетных.
        ),
        'isodd'     => array( // ISODD - Возвращает значение "ИСТИНА" для
            'args'  => true,  // нечетных чисел и значение "ЛОЖЬ" — для четных.
            'func'  => '_isOdd'
        ),
        'lcm'       => array( // LCM (LCM_ADD, НОК) - Возвращает наименьшее
            'args'  => true,  // общее кратное для одного или нескольких целых
            'func'  => '_lcm' // чисел.
        ),
        'lcm_add'   => array( // LCM (LCM_ADD, НОК) - Возвращает наименьшее
            'args'  => true,  // общее кратное для одного или нескольких целых
            'func'  => '_lcm' // чисел.
        ),
        'ln'        => array( // LN - Natural logarithm.
            'args'  => true,
            'func'  => '_ln'
        ),
        'log'       => array( // LOG(arg;base) - логарифм числа по указанному
            'args'  => true,  // основанию.
            'func'  => '_log'
        ),
        'log10'     => array( // LOG10 - Base-10 logarithm.
            'args'  => true,
            'func'  => '_log10'
        ),
        'min'       => array( // MIN - минимум
            'args'  => true,
            'func'  => '_min'
        ),
        'not'       => array( // NOT (НЕ) - логическое отрицание
            'args'  => true,
            'func'  => '_not'
        ),
        'odd'       => array( // ODD (НЕЧЁТ) - Округляет неотрицательное число в
            'args'  => true,  // большую сторону до ближайшего нечетного целого,
            'func'  => '_odd' // а отрицательное число — в меньшую сторону до
        ),                    // ближайшего нечетного целого.
        'or'        => array( // OR (ИЛИ) - логическая функция
            'args'  => true,
            'func'  => '_or'
        ),
        'pi'        => array( // ПИ - число pi
            'args'  => false,
            'func'  => '_pi'
        ),
        'power'     => array( // POWER - число, возведенное в степень.
            'args'  => true,
            'func'  => '_power'
        ),
        'product'   => array( // PRODUCT - произведение нескольких аргументов
            'args'  => true,
            'func'  => '_product'
        ),
        'round'      => array( // ROUND (ОКРУГЛ) - округляет число
            'args'  => true,
            'func'  => '_round'
        ),
        'sign'      => array( // SIGN (ЗНАК) - сигнум
            'args'  => true,
            'func'  => '_sign'
        ),
        'sin'       => array( // SIN - синус числа
            'args'  => true,
            'func'  => '_sin'
        ),
        'sinh'      => array( // SINH - гиперболический синус
            'args'  => true,
            'func'  => '_sinh'
        ),
        'sqrt'      => array( // SQRT (КОРЕНЬ) - квадратный корень числа
            'args'  => true,
            'func'  => '_sqrt'
        ),
        'sqrtpi'    => array( // SQRTPI (КОРЕНЬПИ) - квадратный корень из
            'args'  => true,  // (число * ПИ)
            'func'  => '_sqrtPi'
        ),
        'sum'       => array( // SUM - сумма
            'args'  => true,
            'func'  => '_sum'
        ),
        'tan'       => array( // TAN - тангенс
            'args'  => true,
            'func'  => '_tan'
        ),
        'tanh'      => array( // TANH - гиперболический тангенс
            'args'  => true,
            'func'  => '_tanh'
        ),
        'true'      => array( // TRUE (ИСТИНА) - возвращает true
            'args'  => false,
            'func'  => '_true'
        ),
    );

    // Список возможных ошибок
    var $_errorsList = array(
        0 => 'Incorrect variable name - "{STR}"',
        1 => 'String formula is empty',
        2 => 'Syntax error in string "{STR}"',
        3 => 'Unknown function name - "{STR}"',
        4 => 'Undue closing bracket ")"',
        5 => 'Undue opening bracket "("',
        6 => 'Division by zero',
        7 => 'Incorrect order of operands',
        8 => 'Incorrect arguments of function "{STR}"',
    );

    // Список допущенных ошибок
    var $_errors = array();

    /*
    Массив значений переменных. Имеет структуру:
    array(
        col_key => array(row_key => value, ..., row_key => value),
        ...
        col_key => array(row_key => value, ..., row_key => value),
    )
    Здесь: col_key - буквенные обозначения столбцов таблицы;
           row_key - целочисленные обозначения строк таблицы.
    */
    var $_vars = array();

    // Список вычисляемых в данный момент переменных
    var $_calculate = array();

    // Конструктор класса
    function Calculator($vars = array())
    {
        $this->setVars($vars);
    }

    // Метод устанавливает переменные.
    function setVars($vars = array())
    {
        if (! is_array($vars)) {
            return;
        }
        foreach ($vars as $var => $value) {
            $this->setVar($var, $value);
        }
    }

    // Метод устанавливает одну переменную.
    function setVar($var, $value)
    {
        $var = strtolower($var);
        preg_match('/([a-z]+)(\d+)/', $var, $m);
        if (! isset($m[0]) || $var != $m[0]) {
            $this->_setError(0, $var);
            return;
        }
        $this->_vars[$m[1]][$m[2]] = $value;
        if (null === $value || '' === $value) {
            unset($this->_vars[$m[1]][$m[2]]);
        }
    }

    // Метод возвращает вычисленное значение переменной.
    function getVarValue($var)
    {
        $var = strtolower($var);
        preg_match('/([a-z]+)(\d+)/', $var, $m);
        if (! isset($m[0]) || $var != $m[0]) {
            return null;
        }
        if (isset($this->_vars[$m[1]][$m[2]])) {
            $value = $this->_vars[$m[1]][$m[2]];
        } else {
            $value = null;
        }
        if (is_string($value) && $value) {
            if ('=' == $value{0}) {
                if (in_array($var, $this->_calculate)) {
                    $value = null;
                } else {
                    $this->_calculate[] = $var;
                    $value = $this->getResult($value);
                }
                unset($this->_calculate[array_search($var, $this->_calculate)]);
            } elseif ("'" == $value{0}) {
                $value = substr($value, 1);
            }
        }
        return $value;
    }

    // Записывает ошибку
    function _setError($num, $str = '')
    {
        $this->_errors[] = 'Error ' . $num . ': '
            . str_replace('{STR}', $str, $this->_errorsList[$num]);
    }

    // Возвращает массив ошибок
    function getErrors()
    {
        return $this->_errors;
    }

    /*
    Парсит формулу и возвращает польскую записи, которая для удобства является
    массивом а не строкой и имеет следующую структуру:
    array(
        ключ => array(значение элемента, 'oper' или 'var' или 'const')
    )
    Метод получает на вход строку формулы.
    */
    function _parse($formula)
    {
        $polish = array();
        if (! strlen($formula)) {
            $this->_setError(1);
            return false;
        }
        if ('=' == $formula{0}) {
            $formula = substr($formula, 1);
        }
        $formula = trim($formula);
        if (! strlen($formula)) {
            $this->_setError(1);
            return false;
        }
        $stack = array();
        $prev = '';
        $preg = '/
            \s*\(\s*                   | # Левая скобка
            \s*\)\s*                   | # Правая скобка
            ([A-z]+\d+\s*:\s*[A-z]+\d+)| # Диапазон переменных
            \s*;\s*                    | # Объединение диапазонов переменных
            \s*=\s*                    | # Сравнение - "равно"
            \s*<=\s*                   | # Сравнение - "меньше или равно"
            \s*>=\s*                   | # Сравнение - "больше или равно"
            \s*<>\s*                   | # Сравнение - "не равно"
            \s*<\s*                    | # Сравнение - "меньше"
            \s*>\s*                    | # Сравнение - "больше"
            \s*&\s*                    | # Конкатенация строк
            \s*\+\s*                   | # Сложение или унарный плюс
            \s*-\s*                    | # Вычитание или унарный минус
            \s*\*\s*                   | # Умножение
            \s*\/\s*                   | # Деление
            \s*\^\s*                   | # Возведение в степень
            \s*%\s*                    | # Процент
            \s*[\d\.,]+\s*             | # Числа
            \s*\'[^\']*\'\s*           | # Строковые константы в апострофах
            \s*"[^"]*"\s*              | # Строковые константы в кавычках
            (\s*\w+\s*\(\s*)           | # Имена функций
            \s+                        | # Пересечение диапазонов переменных
            [A-z]+\d+\s*               | # Имена переменных
            (.+)                         # Всякие ошибочные подстроки
            /x';
        while (preg_match($preg, $formula, $match)) {
            if (isset($match[3])) {
                $this->_setError(2, $match[3]);
                return false;
            }
            $str = trim($match[0]);
            if ('' === $str) {
                $str = ' ';
            }
            if (isset($match[1]) && $match[1]) {
                $str = strtolower($str);
                list($left, $right) = explode(':', $str);
                $left = trim($left);
                preg_match('/[a-z]+\d+/', $left, $m);
                $polish[] = array($left, 'var');
                $right = trim($right);
                preg_match('/[a-z]+\d+/', $right, $m);
                $polish[] = array($right, 'var');
                $str = ':';
            }
            if (isset($match[2]) && $match[2]) {
                $str = strtolower($str);
                list($name, $left) = explode('(', $str);
                $name = trim($name);
                if (isset($this->_functions[$name])) {
                    if (! $stack) {
                        array_unshift(
                            $stack, array($name, $this->_priors['f'])
                        );
                    } else {
                        while ($this->_priors['f'] <= $stack[0][1]) {
                            $oper = array_shift($stack);
                            $polish[] = array($oper[0], 'oper');
                            if (! $stack) {
                                break;
                            }
                        }
                        array_unshift(
                            $stack, array($name, $this->_priors['f'])
                        );
                    }
                } else {
                    $this->_setError(3, $name);
                    return false;
                }
                $str = '(';
            }
            if ('-' == $str || '+' == $str) {
                $is_monadic = (
                    '' == $prev || '(' == $prev || ';' == $prev || '=' == $prev
                    || '<=' == $prev || '>=' == $prev || '<>' == $prev
                    || '<' == $prev || '>' == $prev || '&' == $prev
                    || '+' == $prev || '-' == $prev || '*' == $prev
                    || '/' == $prev || '^' == $prev
                );
                if ($is_monadic) {
                    $str .= 'm';
                }
            }
            $prev = $str;
            switch ($str) {
                case '(':
                    array_unshift($stack, array('(', $this->_priors['(']));
                    break;
                case ')':
                    while ($oper = array_shift($stack)) {
                        if ('(' == $oper[0]) {
                            break;
                        }
                        $polish[] = array($oper[0], 'oper');
                    }
                    if (null == $oper) {
                        $this->_setError(4);
                        return false;
                    }
                    break;
                case ':' :
                case ';' :
                case ' ' :
                case '=' :
                case '<=':
                case '>=':
                case '<>':
                case '<' :
                case '>' :
                case '&' :
                case '+' :
                case '-' :
                case '+m':
                case '-m':
                case '*' :
                case '/' :
                case '^' :
                case '%' :
                    if (! $stack) {
                        array_unshift(
                            $stack, array($str, $this->_priors[$str])
                        );
                        break;
                    }
                    while ($this->_priors[$str] <= $stack[0][1]) {
                        $oper = array_shift($stack);
                        $polish[] = array($oper[0], 'oper');
                        if (! $stack) {
                            break;
                        }
                    }
                    array_unshift(
                        $stack, array($str, $this->_priors[$str])
                    );
                    break;
                default:
                    if ('0' == $str{0} || (int) $str) {
                        $str = (float) str_replace(',', '.', $str);
                        $polish[] = array($str, 'const');
                        break;
                    }
                    if ('"' == $str{0} || "'" == $str{0}) {
                        $polish[] = array(substr($str, 1, -1), 'const');
                        break;
                    }
                    $str = strtolower($str);
                    $polish[] = array($str, 'var');
            }
            $formula = substr($formula, strlen($match[0]));
        }
        while ($oper = array_shift($stack)) {
            if ('(' == $oper[0]) {
                $this->_setError(5);
                return false;
            }
            $polish[] = array($oper[0], 'oper');
        }
        return $polish;
    }

    // Возвращает результат вычислений
    function getResult($formula)
    {
        if (! $polish = $this->_parse($formula)) {
            return null;
        }
        $stack = array();
        foreach ($polish as $key => $item) {
            switch ($item[1]) {
                case 'const':
                    array_unshift($stack, $item[0]);
                    break;
                case 'var':
                    array_unshift($stack, $this->getVarValue($item[0]));
                    break;
                case 'oper':
                    switch ($item[0]) {
                        case ';' :
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            if (! is_array($arg1) || ! isset($arg1[0])) {
                                $arg1 = array($arg1);
                            }
                            $arg1[] = $arg2;
                            array_unshift($stack, $arg1);
                            break;
                        case ':' :
                            array_shift($stack);
                            array_shift($stack);
                            $var1 = $polish[$key - 2][0];
                            $var2 = $polish[$key - 1][0];
                            $range = $this->_getRange($var1, $var2);
                            array_unshift($stack, $range);
                            break;
                        case ' ' :
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            array_unshift(
                                $stack, $this->_getIntersection($arg1, $arg2)
                            );
                            break;
                        case '=' :
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            array_unshift($stack, $arg1 == $arg2);
                            break;
                        case '<=':
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            array_unshift($stack, $arg1 <= $arg2);
                            break;
                        case '>=':
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            array_unshift($stack, $arg1 >= $arg2);
                            break;
                        case '<>':
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            array_unshift($stack, $arg1 != $arg2);
                            break;
                        case '<' :
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            array_unshift($stack, $arg1 < $arg2);
                            break;
                        case '>' :
                            $arg2 = array_shift($stack);
                            $arg1 = array_shift($stack);
                            array_unshift($stack, $arg1 > $arg2);
                            break;
                        case '&' :
                            $arg2 = (string) array_shift($stack);
                            $arg1 = (string) array_shift($stack);
                            array_unshift($stack, $arg1 . $arg2);
                            break;
                        case '+' :
                            $arg2 = (float) array_shift($stack);
                            $arg1 = (float) array_shift($stack);
                            array_unshift($stack, $arg1 + $arg2);
                            break;
                        case '-' :
                            $arg2 = (float) array_shift($stack);
                            $arg1 = (float) array_shift($stack);
                            array_unshift($stack, $arg1 - $arg2);
                            break;
                        case '+m':
                            $arg = (float) array_shift($stack);
                            array_unshift($stack, $arg);
                            break;
                        case '-m':
                            $arg = (float) array_shift($stack);
                            array_unshift($stack, (-$arg));
                            break;
                        case '*' :
                            $arg2 = (float) array_shift($stack);
                            $arg1 = (float) array_shift($stack);
                            array_unshift($stack, $arg1 * $arg2);
                            break;
                        case '/' :
                            $arg2 = (float) array_shift($stack);
                            $arg1 = (float) array_shift($stack);
                            if (0 == $arg2) {
                                $this->_setError(6);
                                return null;
                            }
                            array_unshift($stack, $arg1 / $arg2);
                            break;
                        case '^' :
                            $arg2 = (float) array_shift($stack);
                            $arg1 = (float) array_shift($stack);
                            array_unshift($stack, pow($arg1, $arg2));
                            break;
                        case '%' :
                            $arg = (float) array_shift($stack);
                            array_unshift($stack, $arg / 100);
                            break;
                        default:
                            $func = $this->_functions[$item[0]]['func'];
                            if ($this->_functions[$item[0]]['args']) {
                                $arg = array_shift($stack);
                                $val = $this->$func($arg);
                            } else {
                                $val = $this->$func();
                            }
                            $error = is_float($val)
                                && (is_nan($val) || is_infinite($val));
                            if ($error) {
                                $this->_setError(8, $item[0]);
                                return null;
                            }
                            array_unshift($stack, $val);
                    }
            }
        }
        if (1 < count($stack)) {
            $this->_setError(7);
            return null;
        }
        return array_shift($stack);
    }

    // Возвращает диапазон переменных
    function _getRange($var_name1, $var_name2)
    {
        preg_match('/([a-z]+)(\d+)/', $var_name1, $m);
        $col1 = $m[1];
        $row1 = (int) $m[2];
        preg_match('/([a-z]+)(\d+)/', $var_name2, $m);
        $col2 = $m[1];
        $row2 = (int) $m[2];
        if ($row1 > $row2) {
            $row = $row1;
            $row1 = $row2;
            $row2 = $row;
        }
        if ($col1 == $col2 && isset($this->_vars[$col1])) {
            $result = array($row1 => null);
            if (isset($this->_vars[$col1])) {
                foreach ($this->_vars[$col1] as $row => $val) {
                    if ($row1 <= $row && $row <= $row2) {
                        $result[$row] = $this->getVarValue($col1 . $row);
                    }
                }
            }
            if (! isset($result[$row2])) {
                $result[$row2] = null;
            }
            return array($col1 => $result);
        }
        $col1_bigger = (
            strlen($col1) > strlen($col2)
            || (strlen($col1) == strlen($col2) && $col1 > $col2)
        );
        if ($col1_bigger) {
            $col = $col1;
            $col1 = $col2;
            $col2 = $col;
        }
        $result = array($col1 => array($row1 => null, $row2 => null));
        foreach ($this->_vars as $key => $var) {
            $good_key = ((
                    strlen($key) > strlen($col1)
                    || (strlen($key) == strlen($col1) && $key >= $col1)
                ) && (
                    strlen($key) < strlen($col2)
                    || (strlen($key) == strlen($col2) && $key <= $col2)
                )
            );
            if ($good_key) {
                $row = $this->_getRange($key . $row1, $key . $row2);
                $result[$key] = $row[$key];
            }
        }
        if (! isset($result[$col2])) {
            $result[$col2] = array($row1 => null, $row2 => null);
        }
        return $result;
    }

    // Возвращает пересечение диапазонов переменных
    function _getIntersection($range1, $range2)
    {
        if (! is_array($range1) || ! is_array($range2)) {
            return null;
        }
        if (! $range1 || ! $range2) {
            return null;
        }
        $min_row = 0;
        $max_row = 0;
        $begin1_key = 0;
        $end1_key = 0;
        $change1 = false;
        foreach ($range1 as $key => $val) {
            if ($this->_getColNum($key)) {
                if (! is_array($val)) {
                    return null;
                }
                $keys = array_keys($val);
                sort($keys);
                $min_row = (int) array_shift($keys);
                if ($keys) {
                    $max_row = (int) array_pop($keys);
                } else {
                    $max_row = $min_row;
                }
                break;
            }
            if ($change1 && $key < $begin1_key) {
                $begin1_key = $key;
            }
            if ($change1 && $key > $end1_key) {
                $end1_key = $key;
            }
            if (! $change1) {
                $begin1_key = $key;
                $end1_key = $key;
                $change1 = true;
            }
            if (! isset($range2[$key])) {
                unset($range1[$key]);
            }
        }
        $begin2_key = 0;
        $end2_key = 0;
        $change2 = false;
        foreach ($range2 as $key => $val) {
            if ($this->_getColNum($key)) {
                if (! is_array($val)) {
                    return null;
                }
                $keys = array_keys($val);
                sort($keys);
                $min = (int) array_shift($keys);
                if ($keys) {
                    $max = (int) array_pop($keys);
                } else {
                    $max = $min;
                }
                if ($min > $min_row) {
                    $min_row = $min;
                }
                if ($max < $max_row) {
                    $max_row = $max;
                }
                break;
            }
            if ($change2 && $key < $begin2_key) {
                $begin2_key = $key;
            }
            if ($change2 && $key > $end2_key) {
                $end1_key = $key;
            }
            if (! $change2) {
                $begin2_key = $key;
                $end2_key = $key;
                $change2 = true;
            }
            if (! isset($range1[$key])) {
                unset($range2[$key]);
            }
        }
        if ($change1 && $change2) {
            if ($begin1_key < $begin2_key) {
                $begin_key = $begin2_key;
            } else {
                $begin_key = $begin1_key;
            }
            if ($end1_key > $end2_key) {
                $end_key = $end2_key;
            } else {
                $end_key = $end1_key;
            }
            if ($begin_key > $end_key) {
                return null;
            }
            if (! isset($range1[$begin_key])) {
                $range1[$begin_key] = null;
            }
            if (! isset($range1[$end_key])) {
                $range1[$end_key] = null;
            }
            return $range1;
        }
        if ($change1 || $change2) {
            return null;
        }
        if ($min_row > $max_row) {
            return null;
        }
        $begin1_key = '';
        $begin1_num = 0;
        $end1_key = '';
        $end1_num = 0;
        $change1 = false;
        foreach ($range1 as $key => $val) {
            $col_num = $this->_getColNum($key);
            if (! $col_num) {
                return null;
            }
            if ($change1 && $col_num < $begin1_num) {
                $begin1_key = $key;
                $begin1_num = $col_num;
            }
            if ($change1 && $col_num > $end1_num) {
                $end1_key = $key;
                $end1_num = $col_num;
            }
            if (! $change1) {
                $begin1_key = $key;
                $begin1_num = $col_num;
                $end1_key = $key;
                $end1_num = $col_num;
                $change1 = true;
            }
            if (! isset($range2[$key])) {
                unset($range1[$key]);
            } else {
                $range1[$key] = $this->_getIntersection($val, $range2[$key]);
                if (! is_array($range1[$key])) {
                    return null;
                }
                if (! isset($range1[$key][$min_row])) {
                    $range1[$key][$min_row] = null;
                }
                if (! isset($range1[$key][$max_row])) {
                    $range1[$key][$max_row] = null;
                }
            }
        }
        $begin2_key = '';
        $begin2_num = 0;
        $end2_key = '';
        $end2_num = 0;
        $change2 = false;
        foreach ($range2 as $key => $val) {
            $col_num = $this->_getColNum($key);
            if (! $col_num) {
                return null;
            }
            if ($change2 && $col_num < $begin2_num) {
                $begin2_key = $key;
                $begin2_num = $col_num;
            }
            if ($change2 && $col_num > $end2_num) {
                $end2_key = $key;
                $end2_num = $col_num;
            }
            if (! $change2) {
                $begin2_key = $key;
                $begin2_num = $col_num;
                $end2_key = $key;
                $end2_num = $col_num;
                $change2 = true;
            }
            if (! isset($range1[$key])) {
                unset($range2[$key]);
            }
        }
        if ($change1 || $change2) {
            if ($begin1_num < $begin2_num) {
                $begin_key = $begin2_key;
                $begin_num = $begin2_num;
            } else {
                $begin_key = $begin1_key;
                $begin_num = $begin1_num;
            }
            if ($end1_num > $end2_num) {
                $end_key = $end2_key;
                $end_num = $end2_num;
            } else {
                $end_key = $end1_key;
                $end_num = $end1_num;
            }
            if ($begin_num > $end_num) {
                return null;
            }
            if (! isset($range1[$begin_key])) {
                $range1[$begin_key] = array($min_row => null, $max_row => null);
            }
            if (! isset($range1[$end_key])) {
                $range1[$end_key] = array($min_row => null, $max_row => null);
            }
            return $range1;
        }
        return null;
    }

    // Преобразовывает символьный номер столбца в числовой
    function _getColNum($col_str)
    {
        $col_str = strtolower((string) $col_str);
        preg_match('/[a-z]+/', $col_str, $m);
        if (! isset($m[0]) || $col_str != $m[0]) {
            return 0;
        }
        $result = 0;
        $n = strlen($col_str) - 1;
        for ($i = $n; $i >= 0; --$i) {
            $result += (ord($col_str{$i}) - 96) * pow(26, $n-$i);
        }
        return $result;
    }

    // Метод преобразовывает древовидный массив в одномерный.
    function _arrgsToArray($args)
    {
        if (! is_array($args)) {
            return array($args);
        }
        $result = array();
        foreach ($args as $arg) {
            if (! is_array($arg)) {
                $result[] = $arg;
            } else {
                foreach ($this->_arrgsToArray($arg) as $val) {
                    $result[] = $val;
                }
            }
        }
        return $result;
    }

    // ABS - абсолютная величина числа
    function _abs($num)
    {
        return abs((float) $num);
    }

    // ACOS - Arc cosine.
    function _acos($num)
    {
        return acos((float) $num);
    }

    // ACOSH - Inverse hyperbolic cosine.
    function _acosh($num)
    {
        $num = (float) $num;
        if (function_exists('acosh')) {
            return acosh($num);
        }
        return log($num + sqrt(pow($num, 2) - 1));
    }

    // ACOT - Возвращает арккотангенс числа.
    function _acot($num)
    {
        return M_PI/2 - atan((float) $num);
    }

    // ACOTH - Возвращает гиперболический арккотангенс числа.
    function _acoth($num)
    {
        $num = (float) $num;
        if (-1 == $num) {
            return NAN;
        }
        return -1/2 * log(($num-1)/($num+1));
    }

    // AND (И) - логическая функция &&
    function _and($args)
    {
        if (! is_array($args)) {
            return (boolean) $args;
        }
        if (isset($args[0])) {
            foreach ($args as $arg) {
                if ($this->_countblank($arg)) {
                    return false;
                }
            }
        } elseif ($this->_countblank($args)) {
            return false;
        }
        $args = $this->_arrgsToArray($args);
        foreach ($args as $arg) {
            if (! $arg) {
                return false;
            }
        }
        return true;
    }

    // ASIN - Arc sine.
    function _asin($num)
    {
        return asin((float) $num);
    }

    // ASINH - Inverse hyperbolic sine.
    function _asinh($num)
    {
        $num = (float) $num;
        if (function_exists('asinh')) {
            return asinh($num);
        }
        return log($num + sqrt(pow($num, 2) + 1));
    }

    // ATAN - Arc tangent.
    function _atan($num)
    {
        return atan((float) $num);
    }

    // ATAN2 - Arc tangent of two variables.
    function _atan2($args)
    {
        if (! is_array($args)) {
            return NAN;
        }
        $args = $this->_arrgsToArray($args);
        $x = (float) array_shift($args);
        $y = (float) array_shift($args);
        return atan2($y, $x);
    }

    // ATANH - Inverse hyperbolic tangent.
    function _atanh($num)
    {
        $num = (float) $num;
        if (function_exists('atanh')) {
            return atanh($num);
        }
        if (1 == $num) {
            return NAN;
        }
        return 1/2 * log((1 + $num)/(1 - $num));
    }

    // COMBIN(n;k) - Возвращает число сочетаний из n по k без повторений.
    function _combin($args)
    {
        $args = $this->_arrgsToArray($args);
        if (! is_array($args) || 2 > count($args)) {
            return NAN;
        }
        $n = (int) array_shift($args);
        $k = (int) array_shift($args);
        if (0 >= $n || 0 > $k || $n < $k) {
            return NAN;
        }
        if ($n - $k < $k) {
            $k = $n - $k;
        }
        if (0 == $k) {
            return 1;
        }
        if (1 == $k) {
            return $n;
        }
        $combin1 = $this->_combin(array($n-1, $k));
        $combin2 = $this->_combin(array($n-1, $k-1));
        return $combin1 + $combin2;
    }

    // COMBINA(n;k) - Возвращает число сочетаний из n по k с повторениями.
    function _combina($args)
    {
        $args = $this->_arrgsToArray($args);
        if (! is_array($args) || 2 > count($args)) {
            return null;
        }
        $n = (int) array_shift($args);
        $k = (int) array_shift($args);
        if (0 >= $n || 0 > $k || $n < $k) {
            return null;
        }
        if (0 == $k) {
            return 1;
        }
        return $this->_combin(array($n + $k - 1, $k));
    }

    // COS - Cosine.
    function _cos($num)
    {
        return cos((float) $num);
    }

    // COSH - Hyperbolic cosine.
    function _cosh($num)
    {
        return cosh((float) $num);
    }

    // COT - Возвращает котангенс угла.
    function _cot($num)
    {
        return tan(M_PI/2 - (float) $num);
    }

    // COTH - Вычисляет гиперболический котангенс числа (угла).
    function _coth($num)
    {
        $num = (float) $num;
        if (0 == $num) {
            return NAN;
        }
        return cosh($num) / sinh($num);
    }

    // COUNTBLANK - Возвращает количество пустых ячеек в диапазоне.
    function _countblank($range)
    {
        if (! is_array($range) || isset($range[0]) || ! count($range)) {
            return 0;
        }
        if (1 == count($range)) {
            $row = array_shift($range);
            if (! is_array($row) || ! count($row)) {
                return 0;
            }
            $begin = null;
            $end = null;
            $not_null = 0;
            foreach ($row as $key => $val) {
                $key = (int) $key;
                if (null === $begin || $begin > $key) {
                    $begin = $key;
                }
                if (null === $end || $end < $key) {
                    $end = $key;
                }
                if (null !== $val && '' !== $val) {
                    ++$not_null;
                }
            }
            $result = $end - $begin - $not_null + 1;
            if (0 > $result) {
                $result = 0;
            }
            return $result;
        }
        reset($range);
        $row = current($range);
        if (! is_array($row) || ! count($row)) {
            return 0;
        }
        $keys = array_keys($row);
        sort($keys);
        $num1 = (int) array_shift($keys);
        if ($keys) {
            $num2 = (int) array_pop($keys);
        } else {
            $num2 = $num1;
        }
        $col1 = null;
        $col2 = null;
        $nulls = 0;
        foreach ($range as $key => $val) {
            $col1_bigger = (null === $col1)
                || (strlen($col1) > strlen($key))
                || (strlen($col1) == strlen($key) && $col1 > $key);
            if ($col1_bigger) {
                $col1 = $key;
            }
            $col2_less = (null === $col2)
                || (strlen($key) > strlen($col2))
                || (strlen($key) == strlen($col2) && $key > $col2);
            if ($col2_less) {
                $col2 = $key;
            }
            $nulls += $this->_countblank(array($key => $val));
        }
        $begin = 0;
        $end = 0;
        $n = strlen($col1) - 1;
        for ($i = $n; $i >= 0; --$i) {
            $begin += (ord($col1{$i}) - 96) * pow(26, $n-$i);
        }
        $n = strlen($col2) - 1;
        for ($i = $n; $i >= 0; --$i) {
            $end += (ord($col2{$i}) - 96) * pow(26, $n-$i);
        }
        return ($end - $begin - count($range) + 1) * ($num2 - $num1 + 1) + $nulls;
    }

    // DEGREES - Converts the radian number to the equivalent number in degrees.
    function _degrees($num)
    {
        return rad2deg((float) $num);
    }

    /*
    EVEN - Округляет положительное число в большую сторону до ближайшего четного
    целого, а отрицательное число — в меньшую сторону до ближайшего четного
    целого.
    */
    function _even($num)
    {
        $num = (float) $num;
        if (0 < $num) {
            $num = ceil($num);
            if ($num % 2) {
                ++$num;
            }
        } elseif (0 > $num) {
            $num = floor($num);
            if ($num % 2) {
                --$num;
            }
        }
        return $num;
    }

    // EXP - Calculates the exponent of e.
    function _exp($num)
    {
        return exp((float) $num);
    }

    // FACT - Factorial.
    function _fact($num)
    {
        $num = (int) $num;
        if (0 > $num) {
            return NAN;
        }
        if (function_exists('gmp_fact')) {
            $fact = gmp_strval(gmp_fact("$num"));
        } else {
            $fact = 1;
            for ($i = 2; $i <= $num; ++$i) {
                $fact = $fact * $i;
            }
        }
        return $fact;
    }

    // FACTDOUBLE (ДВФАКТР) - двойной факториал
    function _factDouble($num)
    {
        $num = (int) $num;
        if (0 > $num) {
            return NAN;
        }
        $fact = 1;
        for ($i = $num; $i > 1; $i -= 2) {
            $fact = $fact * $i;
        }
        return $fact;
    }

    // FALSE (ЛОЖЬ) - возвращает false
    function _false()
    {
        return false;
    }

    /*
    GCD (GCD_ADD) - The greatest common divisor is the positive largest integer
    which will divide, without remainder, each of the given integers.
    */
    function _gcd($args)
    {
        if (! is_array($args)) {
            return (int) $args;
        }
        if (1 == count($args)) {
            return (int) array_shift($args);
        }
        $x = (int) array_shift($args);
        if (0 >= $x) {
            return NAN;
        }
        $y = $this->_gcd($args);
        if (0 >= $y) {
            return NAN;
        }
        if ($x < $y) {
            $t = $x;
            $x = $y;
            $y = $t;
        }
        while ($t = ($x % $y)) {
            $x = $y;
            $y = $t;
        }
        return $y;
    }

    // IF (ЕСЛИ) - условное выражение
    function _if($args)
    {
        if (! is_array($args)) {
            return NAN;
        }
        $expression = (boolean) array_shift($args);
        $if_true = array_shift($args);
        $if_false = array_shift($args);
        return $expression ? $if_true : $if_false;
    }

    // INT - Округляет число до ближайшего меньшего целого.
    function _int($num)
    {
        return floor((float) $num);
    }

    /*
    ISEVEN - Возвращает значение "ИСТИНА" для четных целых чисел и значение
    "ЛОЖЬ" — для нечетных.
    */
    function _isEven($num)
    {
        return ((int) $num) % 2 ? false : true;
    }

    /*
    ISODD - Возвращает значение "ИСТИНА" для нечетных чисел и значение "ЛОЖЬ" —
    для четных.
    */
    function _isOdd($num)
    {
        return ((int) $num) % 2 ? true : false;
    }

    /*
    LCM (LCM_ADD) - Возвращает наименьшее общее кратное для одного или
    нескольких целых чисел.
    */
    function _lcm($args)
    {
        if (! is_array($args)) {
            return abs((int) $args);
        }
        $y = 1;
        while ($x = array_shift($args)) {
            $x = abs((int) $x);
            if (0 == $x) {
                return NAN;
            }
            $y = $y * $x / $this->_gcd(array($x, $y));
        }
        return $y;
    }

    // LN - Natural logarithm.
    function _ln($num)
    {
        return log((float) $num);
    }

    // LOG(arg;base) - Возвращает логарифм числа по указанному основанию.
    function _log($args)
    {
        if (! is_array($args)) {
            return NAN;
        }
        $args = $this->_arrgsToArray($args);
        $arg = (float) array_shift($args);
        $base = (float) array_shift($args);
        return log($arg, $base);
    }

    // LOG10 - Base-10 logarithm.
    function _log10($num)
    {
        return log10((float) $num);
    }

    // MIN - минимум
    function _min($args)
    {
        if (! is_array($args)) {
            return (float) $args;
        }
        if (isset($args[0])) {
            foreach ($args as $key => $arg) {
                $args[$key] = (float) $arg;
                if (! $arg) {
                    break;
                }
                if ($this->_countblank($arg)) {
                    $args[] = 0;
                    break;
                }
            }
        } elseif ($this->_countblank($args)) {
            $args[] = 0;
        }
        $args = $this->_arrgsToArray($args);
        return min($args);
    }

    // NOT (НЕ) - логическое отрицание
    function _not($arg)
    {
        return ! ((boolean) $arg);
    }

    /*
    ODD - Округляет неотрицательное число в большую сторону до ближайшего
    нечетного целого, а отрицательное число — в меньшую сторону до ближайшего
    нечетного целого.
    */
    function _odd($num)
    {
        $num = (float) $num;
        if (0 <= $num) {
            $num = ceil($num);
            if (! ($num % 2)) {
                ++$num;
            }
        } else {
            $num = floor($num);
            if (! ($num % 2)) {
                --$num;
            }
        }
        return $num;
    }

    // OR (ИЛИ) - логическая функция
    function _or($args)
    {
        if (! is_array($args)) {
            return (boolean) $args;
        }
        $args = $this->_arrgsToArray($args);
        foreach ($args as $arg) {
            if ($arg) {
                return true;
            }
        }
        return false;
    }

    // PI (ПИ) - число pi
    function _pi()
    {
        return M_PI;
    }

    // POWER - Возвращает число, возведенное в степень.
    function _power($args)
    {
        if (! is_array($args)) {
            return NAN;
        }
        $args = $this->_arrgsToArray($args);
        $base = (float) array_shift($args);
        $exp = (float) array_shift($args);
        return pow($base, $exp);
    }

    // PRODUCT - Служит для умножения всех аргументов и получения произведения.
    function _product($args)
    {
        if (! is_array($args)) {
            return (float) $args;
        }
        if (isset($args[0])) {
            foreach ($args as $arg) {
                if ($this->_countblank($arg)) {
                    return 0;
                }
            }
        } elseif ($this->_countblank($args)) {
            return 0;
        }
        $args = $this->_arrgsToArray($args);
        $result = 1;
        foreach ($args as $val) {
            $val = (float) $val;
            $result = $result * $val;
        }
        return $result;
    }

    // ROUND (ОКРУГЛ) - округляет число
    function _round($args)
    {
        $args = $this->_arrgsToArray($args);
        $val = (float) array_shift($args);
        $precision = (int) array_shift($args);
        return round($val, $precision);
    }

    // SIGN (ЗНАК) - сигнум
    function _sign($num)
    {
        $num = (float) $num;
        return (0 < $num) ? 1 : ((0 > $num) ? -1 : 0);
    }

    // SIN - синус
    function _sin($num)
    {
        return sin((float) $num);
    }

    // SINH - гиперболический синус
    function _sinh($num)
    {
        return sinh((float) $num);
    }

    // SQRT - квадратный корень
    function _sqrt($num)
    {
        return sqrt((float) $num);
    }

    // SQRTPI (КОРЕНЬПИ) - квадратный корень из значения выражения (число * ПИ).
    function _sqrtPi($num)
    {
        return sqrt(((float) $num)*M_PI);
    }

    // SUM - сумма
    function _sum($args)
    {
        if (! is_array($args)) {
            return (float) $args;
        }
        $args = $this->_arrgsToArray($args);
        $result = 0;
        foreach ($args as $val) {
            $val = (float) $val;
            $result += $val;
        }
        return $result;
    }

    // TAN - тангенс
    function _tan($num)
    {
        return tan((float) $num);
    }

    // TANH - гиперболический тангенс
    function _tanh($num)
    {
        return tanh((float) $num);
    }

    // TRUE (ИСТИНА) - возвращает true
    function _true()
    {
        return true;
    }
}