<?php


namespace App\Domain\Service;


final class TextUtils
{
    private static $bytePrefixes = array(
        null, 'К', 'М', 'Г', 'Т', 'П'
    );
    
    public static function russianFriendlyFileSize($size, $precision = 2): string
    {
        if ($size < 1024) {
            return
                $size . ' ' . self::selectCaseForNumber(
                    $size, ['байт', 'байта', 'байт']
                );
        }
        
        return self::friendlyFileSize(
                $size, $precision, self::$bytePrefixes, true
            ) . 'Б';
    }
    
    /**
     * Selects russian case for number.
     * for example:
     *    1 результат
     *    2 результата
     *    5 результатов
     *
     * @param int      $number integer
     * @param string[] $cases  words to select from array('результат', 'результата', 'результатов')
     **/
    public static function selectCaseForNumber(int $number, array $cases)
    {
        if (($number % 10) === 1 && ($number % 100) !== 11) {
            return $cases[0];
        }
        
        if (($number % 10) > 1
            && ($number % 10) < 5
            && ($number % 100 < 10 || $number % 100 > 20)) {
            
            return $cases[1];
            
        }
        
        return $cases[2];
    }
    
    public static function friendlyFileSize(
        $size,
        $precision = 2,
        $units = [null, 'k', 'M', 'G', 'T', 'P'],
        $spacePunctuation = false
    ) {
        if ($size > 0) {
            $index = min((int)log($size, 1024), count($units) - 1);
            
            return
                round($size / pow(1024, $index), $precision)
                . ($spacePunctuation ? ' ' : null)
                . $units[$index];
        }
        
        return 0;
    }
}