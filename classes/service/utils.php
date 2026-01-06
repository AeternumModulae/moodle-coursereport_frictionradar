<?php
namespace tool_frictionradar\service;

defined('MOODLE_INTERNAL') || die();

class utils {
    public static function clamp(float $v, float $min, float $max): float {
        return max($min, min($max, $v));
    }

    public static function median(array $values): float {
        $n = count($values);
        if ($n === 0) {
            return 0.0;
        }
        sort($values, SORT_NUMERIC);
        $mid = intdiv($n, 2);
        if ($n % 2) {
            return (float)$values[$mid];
        }
        return ((float)$values[$mid - 1] + (float)$values[$mid]) / 2.0;
    }

    public static function percentile(array $values, float $p): float {
        $n = count($values);
        if ($n === 0) {
            return 0.0;
        }
        sort($values, SORT_NUMERIC);
        $idx = ($n - 1) * ($p / 100.0);
        $lo = (int)floor($idx);
        $hi = (int)ceil($idx);
        if ($lo === $hi) {
            return (float)$values[$lo];
        }
        $w = $idx - $lo;
        return (float)$values[$lo] * (1.0 - $w) + (float)$values[$hi] * $w;
    }

    public static function iqr(array $values): float {
        $q1 = self::percentile($values, 25);
        $q3 = self::percentile($values, 75);
        return max(1e-9, $q3 - $q1);
    }

    /**
     * Map a raw value to 0..100 using robust z-score against the provided population.
     * Direction: 'high' means higher raw -> higher score; 'low' means lower raw -> higher score.
     */
    public static function robust_score(float $raw, array $population, string $direction = 'high'): float {
        if (count($population) < 5) {
            // Fallback: assume raw already roughly 0..1 ratio.
            $v = self::clamp($raw, 0.0, 1.0);
            return $direction === 'high' ? $v * 100.0 : (1.0 - $v) * 100.0;
        }
        $med = self::median($population);
        $iqr = self::iqr($population);
        $z = ($raw - $med) / $iqr;
        if ($direction === 'low') {
            $z = -$z;
        }
        // Piecewise mapping similar to the earlier z buckets.
        if ($z <= -1.0) return 0.0;
        if ($z <= -0.5) return 25.0;
        if ($z <= 0.0) return 50.0;
        if ($z <= 0.5) return 75.0;
        return 100.0;
    }

    public static function ts_minus_days(int $days): int {
        return time() - ($days * 86400);
    }
}
