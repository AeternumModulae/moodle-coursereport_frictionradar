<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar\service;


/**
 * Utility helpers for friction calculations.
 *
 * @package    coursereport_frictionradar
 */
class utils
{
    /**
     * Clamp a value between a minimum and maximum.
     *
     * @param float $v Value.
     * @param float $min Minimum.
     * @param float $max Maximum.
     * @return float Clamped value.
     */
    public static function clamp(float $v, float $min, float $max): float {
        return max($min, min($max, $v));
    }

    /**
     * Calculate the median value of a list.
     *
     * @param array $values Values.
     * @return float Median.
     */
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

    /**
     * Calculate a percentile value.
     *
     * @param array $values Values.
     * @param float $p Percentile.
     * @return float Percentile value.
     */
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

    /**
     * Calculate the interquartile range.
     *
     * @param array $values Values.
     * @return float IQR.
     */
    public static function iqr(array $values): float {
        $q1 = self::percentile($values, 25);
        $q3 = self::percentile($values, 75);
        return max(1e-9, $q3 - $q1);
    }

    /**
     * Map a raw value to 0..100 using robust z-score against the provided population.
     * Direction: 'high' means higher raw -> higher score; 'low' means lower raw -> higher score.
     *
     * @param float $raw Raw value.
     * @param array $population Population values.
     * @param string $direction Direction.
     * @return float Score between 0 and 100.
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
        if ($z <= -1.0) {
            return 0.0;
        }
        if ($z <= -0.5) {
            return 25.0;
        }
        if ($z <= 0.0) {
            return 50.0;
        }
        if ($z <= 0.5) {
            return 75.0;
        }
        return 100.0;
    }

    /**
     * Return timestamp for now minus a number of days.
     *
     * @param int $days Days to subtract.
     * @return int Unix timestamp.
     */
    public static function ts_minus_days(int $days): int {
        return time() - ($days * DAYSECS);
    }
}
