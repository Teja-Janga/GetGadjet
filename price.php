
<?php
function formatIndianCurrency($number) {
    $number = explode('.', $number);
    $num = $number[0];
    $decimal = isset($number[1]) ? '.' . $number[1] : '';
    $result = '';
    $num_length = strlen($num);

    if ($num_length > 3) {
        $last3 = substr($num, -3);
        $remaining = substr($num, 0, $num_length - 3);
        $result = ',' . $last3;
        $remaining = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $remaining);
        $result = $remaining . $result;
    } else {
        $result = $num;
    }
    return $result . $decimal;
}
